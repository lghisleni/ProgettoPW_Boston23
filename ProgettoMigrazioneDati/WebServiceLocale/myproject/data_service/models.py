from django.db import models

# Create your models here.
class Libro(models.Model):
    codISBN = models.CharField(max_length=13, unique=True)
    titolo = models.CharField(max_length=200)
    anno = models.IntegerField()

    def __str__(self):
        return self.titolo

class Pagina(models.Model):
    numero_pagina = models.IntegerField()
    libro = models.CharField(max_length=20)

    def __str__(self):
        return f"Libro {self.libro}, Pagina {self.numero_pagina}"
    
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

class Ingrediente(models.Model):
    numero = models.IntegerField(primary_key=True)
    numeroRicetta = models.ForeignKey(Ricetta, on_delete=models.CASCADE, related_name='ingredienti')
    ingrediente = models.CharField(max_length=200)
    quantita = models.IntegerField()

    def str(self):
        return self.ingrediente
    
class RicettaPubblicata(models.Model):
    numeroRicetta = models.ForeignKey(Ricetta, on_delete=models.CASCADE)
    libro = models.ForeignKey(Libro, on_delete=models.CASCADE)
    numeroPagina = models.ForeignKey(Pagina, on_delete=models.CASCADE)

    def str(self):
        return f"Ricetta {self.numeroRicetta.numero} in Libro {self.libro.codISBN} a Pagina {self.numeroPagina.numero_pagina}"
    
class RicettaRegionale(models.Model):
    regione = models.ForeignKey(Regione, on_delete=models.CASCADE)
    ricetta = models.ForeignKey(Ricetta, on_delete=models.CASCADE)

    class Meta:
        unique_together = ('regione', 'ricetta')

    def str(self):
        return f"Ricetta {self.ricetta.titolo} della Regione {self.regione.nome}"