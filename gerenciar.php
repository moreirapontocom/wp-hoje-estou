<?php 
$message = '';
$warning = '';

if ( isset( $_POST['hojeestou'] ) ) {
		
	$hoje_estou = new hojeestou();

	if ( isset( $_POST['sentimentoAtual'] ) ) {
		$sentimentoAtual = $_POST['sentimentoAtual'];
		$seleciona = $hoje_estou->selectStatus($sentimentoAtual);
		if ( $seleciona )
	        $message = array('updated','Status atualizado');
	    else
	        $message = array('error','Erro ao atualizar o status: '.$_POST['sentimentoAtual']);
	    $warning = '<div class="'.$message[0].'" id="message"><p>'.$message[1].'</p></div>';
	}
		
		
	$button = $_POST['button'];
    if ( $button == "Atualizar" ) {
		if ( isset( $_POST['idItem'] ) && isset( $_POST['valueToUpdate'] ) ) {
			$apaga = $hoje_estou->atualiza_sentimento($_POST['idItem'],$_POST['valueToUpdate']);
			if ( $apaga )
		        $message = array('updated','Item atualizado');
		    else
		        $message = array('error','Erro ao atualizar o item: id: '.$_POST['idToUpdate'].' value: '.$_POST['valueToUpdate']);
		    $warning = '<div class="'.$message[0].'" id="message"><p>'.$message[1].'</p></div>';
		}

    } elseif ( $button == "Apagar" ) {
		if ( isset( $_POST['idItem'] ) ) {
			$apaga = $hoje_estou->apaga_sentimento($_POST['idItem']);
			if ( $apaga )
		        $message = array('updated','Item apagado');
		    else
		        $message = array('error','Erro ao apagar o item');
		    $warning = '<div class="'.$message[0].'" id="message"><p>'.$message[1].'</p></div>';
		}
	}

}
?>

<div class="wrap">
    <div id="icon-plugins" class="icon32"><br /></div> 
    <h2>Hoje estou...</h2>
    
    <?php echo $warning; ?>
    
    <h3>Como você está hoje?</h3>
    <p>
    	<small>Você também pode alterar o status no menu <i>Widgets</i>, dentro de <i>Aparência</i>.</small>
	    <form action="<?php $PHP_SELF; ?>" method="post">
	    	<input type="hidden" name="hojeestou" value="1" />
	    	<?php
	    	$estado_atual = get_option('hojeestou');
	    	
	    	$lista_status = new hojeestou();
            $results_status = $lista_status->lista_sentimentos();
			
			$caminho = get_bloginfo('url').'/wp-content/plugins/hojeestou';
	    	?>
	    	<ul>
	            <?php
	            foreach ( $results_status as $itens ) {
	            	?>
	            	<li style="width: 250px;float: left;margin-bottom: 7px;">
	                	<input type="radio" name="sentimentoAtual" value="<?php echo $itens->id; ?>" onclick="this.form.submit()" <?php if ( $estado_atual == $itens->id ) { echo 'checked="checked"'; } ?>> <img src="<?php echo $caminho; ?>/imagens/<?php echo $itens->image; ?>" height="22" width="22" /> <?php echo $itens->name; ?>
	                </li>
	                <?php
	            }
				?>
			</ul>
	    </form>
    </p>
    
    <br /><br /><br />
    
    <div class="clear"></div>
    
    <hr />
    
    <br />
    
    <h3>Cuide dos seus sentimentos</h3>
    <p>
    	<?php
    	$lista_status = new hojeestou();
        $results_status = $lista_status->lista_sentimentos(1);
    	?>
    	<ul>
            <?php
            foreach ( $results_status as $itens ) {
            	?>
            	<li style="width: 25%;float: left;margin-bottom: 7px;border-right: solid 1px #CCC;padding-left: 15px;">
            		<form action="<?php $PHP_SELF; ?>" method="post">
            			<input type="hidden" name="hojeestou" value="1" />
             			<input type="hidden" name="idItem" value="<?php echo $itens->id; ?>" />
                 		<input type="text" name="valueToUpdate" value="<?php echo $itens->name; ?>" style="width: 100px;" /> <input type="submit" name="button" class="button-primary" value="Atualizar" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="button" class="button-primary" value="Apagar" />
             		</form>
                </li>
                <?php
            }
			?>
		</ul>
    </p>
    
</div>