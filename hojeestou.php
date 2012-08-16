<?php
/*
 * Plugin Name: Hoje Estou
 * Plugin URI: http://lucasmoreira.com.br/plugin-wordpress-hoje-estou
 * Description: Coloque um ícone ou texto no seu site para mostrar aos seus visitantes como você se sente hoje
 * Version: 1.0
 * Author: Lucas Moreira de Souza
 * Author URI: http://lucasmoreira.com.br
 *
 * Copyright 2012 Lucas Moreira de Souza <moreirapontocom at gmail dot com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */

class hojeestou {

    private static $wpdb;
    private static $info;

    public function inicializar() {
        global $wpdb;

        hojeestou::$wpdb = $wpdb;
        hojeestou::$info['plugin_fpath'] = dirname(__FILE__);
    }

    public function ativar() {
		if (is_null(hojeestou::$wpdb))
			hojeestou::inicializar();

		$createTable = "CREATE TABLE ".hojeestou::$wpdb->prefix."hoje_estou (`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,`name` varchar(100) NOT NULL DEFAULT 'Normal',`image` varchar(100) NOT NULL DEFAULT 'default.png', PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
		$insertStatus = "
			INSERT INTO ".hojeestou::$wpdb->prefix."hoje_estou VALUES (1,'Normal','normal.png'),
(2,'Abobado','abobado.png'),
(3,'Acabado','acabado.png'),
(4,'Alegre','alegre.png'),
(5,'Apaixonado','apaixonado.png'),
(6,'Assustado','assustado.png'),
(7,'Bobo alegre','boboalegre.png'),
(8,'Chorando','chorando.png'),
(9,'Com dores','comdor.png'),
(10,'Com medo','commedo.png'),
(11,'Com raiva','comraiva.png'),
(12,'Com sono','comsono.png'),
(13,'Com vergonha','comvergonha.png'),
(14,'Confuso','confuso.png'),
(15,'Danado','danado.png'),
(16,'Descolado','descolado.png'),
(17,'Distraído','distraido.png'),
(18,'Engraçado','engracado.png'),
(19,'Espantado','espantado.png'),
(20,'Estranho','estranho.png'),
(21,'Indiferente','indiferente.png'),
(22,'Muito feliz','muitofeliz.png'),
(23,'Nem aí','nemai.png'),
(24,'Nem ligando','nemligando.png'),
(25,'Nerd','nerd.png'),
(26,'Nervoso','nervoso.png'),
(27,'Ninja','ninja.png'),
(28,'Palhaço','palhaco.png'),
(29,'Passando mal','passandomal.png'),
(30,'Receoso','receoso.png'),
(31,'Rindo à toa','rindoatoa.png'),
(32,'Risonho','risonho.png'),
(33,'Triste','triste.png'),
(34,'Vesgo','vesgo.png')";

		hojeestou::$wpdb->query($createTable);
		hojeestou::$wpdb->query($insertStatus);

		add_option('hojeestou','1');
   }

    public function desativar() {
        if (is_null(hojeestou::$wpdb))
            hojeestou::inicializar();
            
        $dropTable  = "DROP TABLE `".hojeestou::$wpdb->prefix."hoje_estou`";
        hojeestou::$wpdb->query($dropTable);
		delete_option('hojeestou');
    }

    public function lista_sentimentos($normal='') {
		if ( is_null(hojeestou::$wpdb) )
            hojeestou::inicializar();
		
		if ( $normal <> 1 )
			$lista = "SELECT * FROM ".hojeestou::$wpdb->prefix."hoje_estou ORDER BY name ASC";
		else
			$lista = "SELECT * FROM ".hojeestou::$wpdb->prefix."hoje_estou WHERE id <> 1 ORDER BY name ASC";

		$results = hojeestou::$wpdb->get_results($lista);
		
		if ( $results ) {
			return $results;
		} else {
			return false;
		}
   }
   
	public function apaga_sentimento($id='') {
		if ( is_null(hojeestou::$wpdb) )
            hojeestou::inicializar();

		$id = trim($id);
		if ( !empty($id) && is_numeric($id) ) {
			$delete = "DELETE FROM `".hojeestou::$wpdb->prefix."hoje_estou` WHERE id = ".$id." LIMIT 1";
			$results = hojeestou::$wpdb->query($delete);
			
			if ( $results ) {
				return true;
			} else {
				return false;
			}
		}
	}

	public function atualiza_sentimento($id='',$valor='') {
		if ( is_null(hojeestou::$wpdb) )
            hojeestou::inicializar();

		$id = trim($id);
		$valor = trim($valor);
		if ( !empty($id) && !empty($valor) && is_numeric($id) ) {
			$update = "UPDATE ".hojeestou::$wpdb->prefix."hoje_estou SET name = '".$valor."' WHERE id = ".$id;
			$results = hojeestou::$wpdb->query($update);
			
			if ( $results ) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function get_sentimento($id='') {
		if ( is_null(hojeestou::$wpdb) )
            hojeestou::inicializar();

		$id = trim($id);
		if ( !empty($id) && is_numeric($id) ) {
			$get = "SELECT * FROM ".hojeestou::$wpdb->prefix."hoje_estou WHERE id = '".$id."'";
			$results = hojeestou::$wpdb->get_row($get);
			
			if ( $results ) {
				return $results;
			} else {
				return false;
			}
		}
	}
	

	function menu_hoje_estou() {
		add_options_page('Como eu estou me sentindo hoje?', 'Hoje estou...', 10, 'hojeestou/gerenciar.php');
	}

public function selectStatus($idStatus='') {
	if ( empty( $idStatus ) )
		update_option('hojeestou',1);
	else {
		$idStatus = trim($idStatus);
		$idStatus = substr($idStatus,0,3);
		if ( update_option('hojeestou',$idStatus) )
			return true;
		else
			return false;
	}
}

} // end class

$pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);

//add_action('admin_menu', array('hojeestou', 'criar_menu'));
add_action('admin_menu', array('hojeestou', 'menu_hoje_estou'));
register_activation_hook($pathPlugin, array('hojeestou', 'ativar'));      // activation
register_deactivation_hook($pathPlugin, array('hojeestou', 'desativar')); // deactivation










// definindo o plugin para aparecer na tela de widgets
function hojeestou_config() {
    $options = array();

    if ( $_POST['salvar'] ) : // Se o formulário for submetido, salvamos as infomações
        $sentimentoSelecionado = $_POST['sentimentoAtual'];
        update_option('hojeestou', $sentimentoSelecionado);
    endif;

	$estado_atual = get_option('hojeestou');
	$lista_status = new hojeestou();
    $results_status = $lista_status->lista_sentimentos();
	$caminho = get_bloginfo('url').'/wp-content/plugins/hojeestou';

    echo "<input type='hidden' name='salvar' value='1' /><ul>";
    foreach ( $results_status as $itens ) {
    	( $estado_atual == $itens->id ) ? $selecionado = "checked='checked'" : $selecionado = "";
    	echo "<li><input type='radio' name='sentimentoAtual' value='".$itens->id."'".$selecionado."> <img src='".$caminho."/imagens/".$itens->image."' height='22' width='22' /> ".$itens->name."</li>";
    }
	echo "</ul>";
}

function hojeestou() {
    $pega_sentimento = new hojeestou();
    $results_status = $pega_sentimento->get_sentimento( get_option('hojeestou') );

	foreach ( $results_status as $itens ) {
		$imagemSentimento = $results_status->image;
		$nomeSentimento = $results_status->name;
	}

	// Mostra o widget na sidebar que o usuário o colocou
	echo "
		<style type='text/css'>
			ul.hojeestou {list-style: none;padding: 0;margin: 0;width: 100%;font-size: 12px;color: #333;clear: both;height: auto;overflow: auto;font-weight: normal;}
				.hojeestou li.label {list-style: none;padding: 0;margin: 0;float: left;margin-right: 10px;}
				.hojeestou li.image {list-style: none;padding: 0;margin: 0;float: left;width: 22px;margin-right: 5px;}
				.hojeestou li.name {list-style: none;padding: 0;margin: 0;float: left;font-size: 14px;font-weight: bold;}
		</style>";

    echo "
    	<!-- created by Lucas Moreira [lucasmoreira.com.br] -->
    	<ul class='hojeestou'>
    		<li class='label'>Hoje estou:</li>
    		<li class='image'><img src='".get_bloginfo('url').'/wp-content/plugins/hojeestou/imagens/'.$imagemSentimento."' height='22' width='22' alt='".$nomeSentimento."' /></li>
    		<li class='name'>".$nomeSentimento."</li>
		</ul>";
}

function meuwidget_register() {
    register_sidebar_widget('Hoje estou...', 'hojeestou');
    register_widget_control('Hoje estou...', 'hojeestou_config');
}
add_action('widgets_init', 'meuwidget_register');
?>
