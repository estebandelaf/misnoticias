MiSnOtIcIaS
===========

MiSnOtIcIaS es un sistema de noticias sencillo, programado para ser usado sin
una base de datos.

Características
---------------

- Los archivos de noticias son textos planos.
- Muestra sólo cierta cantidad de noticias en el resumen.
- Noticias tienen la opción *leer más*.
- Paginación de noticias en la vista completa.
- Permite generar RSS.
- Soporte de internacionalización.
- Utiliza plantillas para separar el diseño de la lógica.

Demo
----

El demo del sistema de noticias se puede observar en:

- [Lista completa de noticias](http://mi.delaf.cl/misnoticias)
- [Últimas noticias](http://mi.delaf.cl/misnoticias/ultimas.php)
- [Últimas noticias mediante RSS](http://mi.delaf.cl/misnoticias/rss.php)

Archivos de noticias
--------------------

Los archivos se deben ubicar en el directorio *noticias*, el nombre del archivo
corresponde a la fecha de la noticia, ejemplo: 2011-02-27.txt (formato:
YYYY-MM-DD.txt). Si hay más de una noticia por día será: 2011-02-27.1.txt y
2011-02-27.2.txt El contenido del archivo es:

    Primera línea: título
    Segunda línea: autor
    Tercera línea: introducción
    Cuarta y restantes líneas: cuerpo de la noticia
