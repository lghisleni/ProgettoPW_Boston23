from django.db import models

# Create your models here.
class Libro(models.Model):
    codISBN = models.CharField(max_length=13, unique=True)
    titolo = models.CharField(max_length=200)
    anno = models.IntegerField()

    def __str__(self):
        return self.titolo

class Pagina(models.Model):
    numeroPagina = models.IntegerField()
    libro = models.CharField(max_length=20)

    def __str__(self):
        return f"Libro {self.libro}, Pagina {self.numeroPagina}"
    
class Regione(models.Model):
    cod = models.IntegerField(primary_key=True)
    nome = models.CharField(max_length=100)

    def str(self):
        return self.nome

class Ricetta(models.Model):
    numero = models.IntegerField(primary_key=True)
    titolo = models.CharField(max_length=200)
    tipo = models.CharField(max_length=50)

    def str(self):
        return self.titolo

from django.db import models

class Ingrediente(models.Model):
    id = models.BigAutoField(primary_key=True)
    numero = models.IntegerField()
    numeroRicetta = models.IntegerField()
    ingrediente = models.CharField(max_length=200)
    quantita = models.IntegerField()

    def _str_(self):
        return self.ingrediente

class RicettaPubblicata(models.Model):
    id = models.BigAutoField(primary_key=True)
    numeroRicetta = models.IntegerField()
    libro = models.IntegerField()
    numeroPagina = models.IntegerField()

    def _str_(self):
        return f"Ricetta {self.numeroRicetta} in Libro {self.libro} a Pagina {self.numeroPagina}"

class RicettaRegionale(models.Model):
    id = models.BigAutoField(primary_key=True)
    regione = models.IntegerField()
    ricetta = models.IntegerField()

    class Meta:
        unique_together = ('regione', 'ricetta')

    def _str_(self):
        return f"Ricetta {self.ricetta} della Regione {self.regione}"