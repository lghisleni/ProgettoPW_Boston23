from django.shortcuts import render

# Create your views here.
from rest_framework import viewsets
from .models import Libro, Pagina, Regione, Ricetta, Ingrediente, RicettaPubblicata, RicettaRegionale
from .serializers import LibroSerializer, PaginaSerializer, RegioneSerializer, RicettaSerializer, IngredienteSerializer, RicettaPubblicataSerializer, RicettaRegionaleSerializer

class LibroViewSet(viewsets.ModelViewSet):
    queryset = Libro.objects.all()
    serializer_class = LibroSerializer

class PaginaViewSet(viewsets.ModelViewSet):
    queryset = Pagina.objects.all()
    serializer_class = PaginaSerializer

class RegioneViewSet(viewsets.ModelViewSet):
    queryset = Regione.objects.all()
    serializer_class = RegioneSerializer

class RicettaViewSet(viewsets.ModelViewSet):
    queryset = Ricetta.objects.all()
    serializer_class = RicettaSerializer

class IngredienteViewSet(viewsets.ModelViewSet):
    queryset = Ingrediente.objects.all()
    serializer_class = IngredienteSerializer

class RicettaPubblicataViewSet(viewsets.ModelViewSet):
    queryset = RicettaPubblicata.objects.all()
    serializer_class = RicettaPubblicataSerializer

class RicettaRegionaleViewSet(viewsets.ModelViewSet):
    queryset = RicettaRegionale.objects.all()
    serializer_class = RicettaRegionaleSerializer