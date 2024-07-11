from django.urls import path, include
from rest_framework.routers import DefaultRouter
from .views import LibroViewSet, PaginaViewSet, RegioneViewSet, RicettaViewSet, IngredienteViewSet, RicettaPubblicataViewSet, RicettaRegionaleViewSet

router = DefaultRouter()
router.register(r'libro', LibroViewSet)
router.register(r'pagina', PaginaViewSet)
router.register(r'regione', RegioneViewSet)
router.register(r'ricetta', RicettaViewSet)
router.register(r'ingrediente', IngredienteViewSet)
router.register(r'ricettapubblicata', RicettaPubblicataViewSet)
router.register(r'ricettaregionale', RicettaRegionaleViewSet)

urlpatterns = [
    path('', include(router.urls)),
]