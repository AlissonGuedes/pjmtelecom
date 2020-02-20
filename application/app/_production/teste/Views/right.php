<br>
<?php

 $comunicado=new \App\Models\ComunicadoModel();
 $comunicados=$comunicado -> getComunicado(['datahora_agendamento >='=> date('Y-m-d H:i:s')]);
 
 if ( $comunicados -> numRows() > 0 ) :

?>
<div class="tag_dir">
	COMUNICADOS
</div>
<div class="box_comunicados">
	<?php foreach ( $comunicados -> result() as $comunicado ) :
	?>
	<a href="#modal-comunicado"
	class="modal-trigger"
	data-url="<?php echo base_url(); ?>comunicados/<?php echo $comunicado -> id; ?>"
	data-toggle="modal"
	>
		<div class="comunicado">
			<div class="data_comunicado">
				<div class="dia">
					<?php echo date('d', strtotime($comunicado -> datahora_agendamento)); ?>
				</div>
				<div class="mesano">
					<?php echo date('M/Y', strtotime($comunicado -> datahora_agendamento)); ?>
				</div>
			</div>
			<div class="title_comunicado">
				<?php echo substr($comunicado -> titulo, 0, 75) . '...'; ?>
			</div>
		</div>
	</a>
	<?php endforeach; ?>
</div>
<?php endif; ?>
<div class="tag_dir">
	DÚVIDAS FREQUENTES
</div>
<div class="box_comunicados">
	<?php
	$faq = new \App\Models\FaqModel();
	$faqs = $faq -> getFaq();
	if ( $faqs -> numRows() > 0 )
	:
		foreach ( $faqs -> result() as $faq )
		:
			echo '<a href="#modal-comunicado" data-url="' . base_url() . 'faqs/' . $faq -> id . '" class="modal-trigger" data-toggle="modal"><div class="duvida_title">' . $faq -> titulo . '</div></a>';
		endforeach;
	else
	:
		echo 'Nenhuma pergunta registrada.';
	endif;
?>
</div>
<div id="modal-comunicado" class="modal modal-fixed-footer modal-fixed-header center-align">
	<div class="modal-content">
    	<div class="modal-header">
    		<h6>
    			Título
    		</h6>
    	</div>
    	<div class="modal-body">
    		<h4>
    			Subtítulo
    		</h4>
    		<div class="loading" style="position: absolute; width: 100%; left: 0px; display: block;">
    			Carregando...
    		</div>
    		<div class="content" style="display: none;">
    			<p>
    				Parágrafo
    			</p>
    		</div>
    	</div>
    	<div class="modal-footer">
    		<button class="modal-close waves-effect waves-light btn-flat">
    			<i class="material-icons left">close</i>
    			<span>
    				Fechar
    			</span>
    		</button>
    	</div>
	</div>
</div>