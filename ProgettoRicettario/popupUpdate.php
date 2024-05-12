<div class="popupUpdate" id="popupUpdate">
    <img src="imm/update.png"></img>
    <form name="formUpdate" id="formUpdate" onsubmit="return handleFormSubmit(event)" method="POST">
        <input id="nomeIngredienteUp" name="nomeIngredienteUp" type="text" placeholder="Inserisci il nome"/>
        <input id="quantitaUp" name="quantitaUp" type="text" placeholder="Inserisci la quantità"/>
        <input id="update" type="submit" value="update"/>
    </form>
    <button class="btnBackUpdate" onclick="annulaUpdate()"> Annulla </button>
</div>