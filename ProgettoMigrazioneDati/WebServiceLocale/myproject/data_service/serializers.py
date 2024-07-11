from rest_framework import serializers
from .models import Libro, Pagina, Regione, Ricetta, Ingrediente, RicettaPubblicata, RicettaRegionale

class LibroSerializer(serializers.ModelSerializer):
    class Meta:
        model = Libro
        fields = '__all__'

class PaginaSerializer(serializers.ModelSerializer):
    class Meta:
        model = Pagina
        fields = '__all__'

class RegioneSerializer(serializers.ModelSerializer):
    class Meta:
        model = Regione
        fields = '__all__'

class RicettaSerializer(serializers.ModelSerializer):
    class Meta:
        model = Ricetta
        fields = '__all__'

class IngredienteSerializer(serializers.ModelSerializer):
    class Meta:
        model = Ingrediente
        fields = '__all__'

class RicettaPubblicataSerializer(serializers.ModelSerializer):
    class Meta:
        model = RicettaPubblicata
        fields = '__all__'

class RicettaRegionaleSerializer(serializers.ModelSerializer):
    class Meta:
        model = RicettaRegionale
        fields = '__all__'