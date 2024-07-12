package com.example.webservice;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import org.apache.hc.client5.http.classic.methods.HttpGet;
import org.apache.hc.client5.http.classic.methods.HttpPost;
import org.apache.hc.core5.http.io.entity.StringEntity;
import org.apache.hc.client5.http.impl.classic.CloseableHttpClient;
import org.apache.hc.client5.http.impl.classic.CloseableHttpResponse;
import org.apache.hc.client5.http.impl.classic.HttpClients;
import org.apache.hc.core5.http.io.entity.EntityUtils;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Map;

public class WebserviceServlet extends HttpServlet {

    private static final String ALTERVISTA_URL = "https://programmazioneweblg.altervista.org/ProgettoMigrazioneDati/index.php";
    private static final String DJANGO_API_BASE_URL = "http://127.0.0.1:8000/api/";

    protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
        try (CloseableHttpClient httpClient = HttpClients.createDefault()) {
            // Step 1: Fetch HTML data from Altervista
            HttpGet getRequest = new HttpGet(ALTERVISTA_URL);
            try (CloseableHttpResponse getResponse = httpClient.execute(getRequest)) {
                String htmlString = EntityUtils.toString(getResponse.getEntity(), "UTF-8");

                // Step 2: Extract JSON data from HTML
                String jsonString = extractJsonData(htmlString);
                JsonObject jsonData = JsonParser.parseString(jsonString).getAsJsonObject();

                // Step 3: Parse JSON data and send to Django APIs
                for (Map.Entry<String, JsonElement> entry : jsonData.entrySet()) {
                    String tableName = entry.getKey().toLowerCase();
                    JsonArray tableData = entry.getValue().getAsJsonArray();

                    for (JsonElement element : tableData) {
                        JsonObject record = element.getAsJsonObject();
                        sendToDjangoApi(httpClient, tableName, record);
                    }
                }
            }

            // Send success response
            response.setContentType("application/json");
            response.setCharacterEncoding("UTF-8");
            try (PrintWriter out = response.getWriter()) {
                out.print("{\"status\": \"success\"}");
                out.flush();
            }

        } catch (Exception e) {
            e.printStackTrace();
            response.sendError(HttpServletResponse.SC_INTERNAL_SERVER_ERROR, "An error occurred while processing the request.");
        }
    }

    private String extractJsonData(String html) {
        int start = html.indexOf("const dbData =") + 15;
        int end = html.indexOf("};", start) + 1;
        return html.substring(start, end).trim();
    }

    private void sendToDjangoApi(CloseableHttpClient httpClient, String tableName, JsonObject record) throws IOException {
        String apiUrl = DJANGO_API_BASE_URL + tableName + "/";
        HttpPost postRequest = new HttpPost(apiUrl);
        postRequest.setHeader("Content-Type", "application/json");
        StringEntity entity = new StringEntity(record.toString());
        postRequest.setEntity(entity);

        try (CloseableHttpResponse postResponse = httpClient.execute(postRequest)) {
            if (postResponse.getCode() != 201) {
                throw new RuntimeException("Failed to create record in Django API: " + apiUrl);
            }
        }
    }
}