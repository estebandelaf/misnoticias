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

/**
 * Administrador de noticias
 * @author DeLaF, esteban[at]delaf.cl
 * @version 2011-02-28
 */
class MiSnOtIcIaS {

	private static $nnoticias; ///< número de noticias disponibles
	private static $tab; ///< desde donde se tabulará

	/**
	 * Buscar las noticias disponibles
	 * @return Array Arreglo con el listado de archivos que contienen noticias
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-02-28
	 */
	private static function buscar () {
		$archivosNoticias = array();
		if ($gestor = opendir(MISNOTICIAS_DIR.'/noticias')) { // abrir directorio
			while (($archivo = readdir($gestor)) != false) { // leer directorio
				if($archivo[0]!='.') { // no considerar .*
					array_push($archivosNoticias, $archivo); // guardar nombre del archivo
				}
			}
			closedir($gestor); // cerrar gestor
		}
		unset($gestor, $archivo);
		rsort($archivosNoticias); // ordenar resultado alfabéticamente
		self::$nnoticias = count($archivosNoticias);
		return $archivosNoticias;
	}

	/**
	 * Lee los archivos de las noticias y devuelve un arreglo con la noticia
	 * @param desde Desde que número de noticia se partirá (comenzando de la 1)
	 * @param cantidad cuantas noticias se seleccionaran
	 * @return Array Arreglo con un arreglo por cada noticia con indices: date, title, author, intro, body, pubDate y link
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-02-28
	 */
	private static function cargar ($desde, $cantidad) {
		$noticias = array();
		$archivosNoticias = self::buscar();
		$noticiasDisponibles = count($archivosNoticias);
		--$desde; // decrementar desde para que, por ejemplo, coincida la noticia 1 con el indice 0 en el arreglo
		$hasta = $desde + $cantidad;
		for($i=$desde; $i<$hasta; ++$i) {
			if($i==$noticiasDisponibles) break; // si se alcanzo el limite de noticias disponibles se corta el ciclo
			array_push($noticias, self::leer($archivosNoticias[$i]));
		}
		unset($archivosNoticias);
		return $noticias;
	}

	/**
	 * Lee el archivo de la noticia y devuelve un arreglo con los datos de la misma
	 * @param archivoNoticia archivo de noticia que se deberá leer (con o sin extensión .txt)
	 * @return Array Arreglo con indices: date, title, author, intro, body, pubDate y link
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2015-06-22
	 */
	private static function leer ($archivoNoticia) {
		$archivoNoticia = preg_match('/\.txt$/', $archivoNoticia) ? $archivoNoticia : $archivoNoticia.'.txt';
		$lineas = explode("\n", file_get_contents(MISNOTICIAS_DIR.'/noticias/'.$archivoNoticia));
		$aux = explode('.', $archivoNoticia);
		$noticia['date'] = array_shift($aux);
		$noticia['title'] = array_shift($lineas);
		$noticia['author'] = array_shift($lineas);
		$noticia['intro'] = array_shift($lineas);
		$noticia['body'] = implode(MISNOTICIAS_BODY_GLUE, $lineas);
		$noticia['pubDate'] = date('D, d M Y H:i:s O', filemtime(MISNOTICIAS_DIR.'/noticias/'.$archivoNoticia));
		$noticia['link'] = MISNOTICIAS_URL_NEWS.'?noticia='.substr($archivoNoticia, 0, strrpos($archivoNoticia, '.'));
		return $noticia;
	}

	/**
	 * Genera contenido rss para mostrar las últimas noticias
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-02-28
	 */
	public static function rss () {
		$noticiasXML = '';
		$noticias = self::cargar(1, MISNOTICIAS_RSS_LIMIT);
		foreach($noticias as &$noticia) {
			$noticiasXML .= self::generar('noticia.xml', $noticia, MISNOTICIAS_RSS_TAB);
		}
		echo self::generar('rss.xml', array('titulo'=>MISNOTICIAS_TITLE, 'url_noticias'=>MISNOTICIAS_URL_NEWS, 'url_rss'=>MISNOTICIAS_URL_RSS, 'lang'=>LANG, 'descripcion'=>MISNOTICIAS_DESC, 'noticias'=>$noticiasXML, 'generador'=>MISNOTICIAS_GENERATOR, 'copyright'=>MISNOTICIAS_COPYRIGHT, 'ttl'=>MISNOTICIAS_TTL));
	}

	/**
	 * Muestra la lista de noticias páginadas o bien una noticia específica si es indicada
	 * @param archivoNoticia archivo de noticia que se quiere mostrar
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-02-28
	 */
	public static function mostrar ($archivoNoticia = null) {
		self::$tab = TAB;
		if($archivoNoticia) self::mostrarNoticia($archivoNoticia);
		else self::mostrarNoticias();
	}

	/**
	 * Muestra una noticia específica
	 * @param archivoNoticia archivo de noticia que se quiere mostrar
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-02-28
	 */
	private static function mostrarNoticia ($archivoNoticia) {
		$noticia = self::leer($archivoNoticia);
		unset($archivoNoticia);
		echo self::generar('noticia.html', $noticia, self::$tab);
	}

	/**
	 * Muestra la lista de noticias páginadas
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-02-28
	 */
	private static function mostrarNoticias () {
		$desde = empty($_GET['pagina']) || $_GET['pagina'] == 1 ? 1 : ($_GET['pagina'] - 1) * MISNOTICIAS_PER_PAGE + 1;
		$noticias = self::cargar($desde, MISNOTICIAS_PER_PAGE);
		unset($desde);
		// crear paginador de noticias
		$paginas = ceil(self::$nnoticias/MISNOTICIAS_PER_PAGE);
		$linkPaginas = '';
		for($i=1; $i<=$paginas; ++$i)
			$linkPaginas .= self::generar('linkPaginas.html', array('pagina'=>$i), self::$tab+2);
		unset($paginas);
		// crear noticias
		$noticiasHTML = '';
		foreach($noticias as &$noticia) {
			$noticia['more'] = MISNOTICIAS_LANG_READ_MORE;
			$noticiasHTML .= self::generar('noticiasItem.html', $noticia, self::$tab+2);
		}
		unset($noticias);
		// mostrar pagina
		echo self::generar('noticias.html', array('titulo'=>MISNOTICIAS_LANG_TITLE, 'pages'=>MISNOTICIAS_LANG_PAGE, 'linkPaginas'=>$linkPaginas, 'noticias'=>$noticiasHTML), self::$tab);
	}

	/**
	 * Muestra las últimas noticias
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-02-28
	 */
	public static function mostrarUltimas () {
		self::$tab = TAB;
		$noticias = self::cargar(1, MISNOTICIAS_LAST_LIMIT);
		// crear noticias
		$noticiasHTML = '';
		foreach($noticias as &$noticia) {
			$noticia['more'] = MISNOTICIAS_LANG_READ_MORE;
			$noticiasHTML .= self::generar('ultimasNoticiasItem.html', $noticia, self::$tab+1);
		}
		unset($noticias);
		// mostrar pagina
		echo self::generar('ultimasNoticias.html', array('titulo'=>MISNOTICIAS_LANG_TITLE, 'noticias'=>$noticiasHTML, 'noticias_url'=>MISNOTICIAS_URL_NEWS, 'ver_todas'=>MISNOTICIAS_LANG_ALL), self::$tab);
	}

	/**
	 * Esta método permite utilizar plantillas html en la aplicacion, estas deberán
	 * estar ubicadas en la carpeta template del directorio raiz (de la app)
	 * @param nombrePlantila Nombre del archivo html que se utilizara como plantilla
	 * @param variables Arreglo con las variables a reemplazar en la plantilla
	 * @param tab Si es que se deberán añadir tabuladores al inicio de cada linea de la plantilla
	 * @return String Plantilla ya formateada con las variables correspondientes
	 * @author DeLaF, esteban[at]delaf.cl
	 * @version 2011-03-02
	 */
	public static function generar ($nombrePlantilla, $variables = null, $tab = 0) {

		// definir donde se encuentra la plantilla
		$archivoPlantilla = MISNOTICIAS_DIR.'/template/'.MISNOTICIAS_TEMPLATE.'/'.$nombrePlantilla;

		// cargar plantilla
		$plantilla = file_get_contents($archivoPlantilla);

		// añadir tabuladores delante de cada linea
		if($tab) {
			$lineas = explode("\n", $plantilla);
			foreach($lineas as &$linea) {
				if(!empty($linea)) $linea = constant('TAB'.$tab).$linea;
			}
			$plantilla = implode("\n", $lineas);
			unset($lineas, $linea);
		}

		// reemplazar variables en la plantilla
		if($variables) {
			foreach($variables as $key => $valor)
				$plantilla = str_replace('{'.$key.'}', $valor, $plantilla);
		}

		// retornar plantilla ya procesada
		return $plantilla;

        }

}

?>
