<?php

/**
 * MiSnOtIcIaS
 * Copyright (C) 2008-2011 Esteban De La Fuente Rubio (esteban@delaf.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o modificarlo
 * bajo los términos de la Licencia Pública General GNU publicada
 * por la Fundación para el Software Libre, ya sea la versión 3
 * de la Licencia, o (a su elección) cualquier versión posterior de la misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General GNU para obtener
 * una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/gpl.html>.
 *
 */

date_default_timezone_set('America/Santiago');

define('MISNOTICIAS_TITLE', 'Noticias Proyecto Mi');
define('MISNOTICIAS_DESC', 'Noticias mi.delaf.cl'); // descripcion de las noticias
define('MISNOTICIAS_URL_NEWS', 'http://mi.delaf.cl/misnoticias/'); // direccion web de la vista completa de las noticias (incluído / final)
define('MISNOTICIAS_URL_RSS', 'http://mi.delaf.cl/misnoticias/rss.php'); // direccion web de la fuente rss
define('MISNOTICIAS_DIR', dirname(dirname(__FILE__)));
define('MISNOTICIAS_BODY_GLUE', '<br /><br />');
define('MISNOTICIAS_TEMPLATE', 'default');
define('MISNOTICIAS_LAST_LIMIT', 2); // cantidad de noticias a mostrar en la vista "ultimas noticias"
define('MISNOTICIAS_RSS_LIMIT', 3); // cantidad de noticias a mostrar en el archivo rss
define('MISNOTICIAS_PER_PAGE', 4); // cantidad de noticias a mostrar por pagina en la vista de todas las noticias
define('MISNOTICIAS_RSS_TAB', 2); // tab para diseño de rss
define('MISNOTICIAS_GENERATOR', 'MiSnOtIcIaS');
define('MISNOTICIAS_COPYRIGHT', 'MiSnOtIcIaS');
define('MISNOTICIAS_TTL', 15);

defined('LANG') or define('LANG', 'es');
defined('TAB') or define('TAB', 6);
