<h2>
Faire un dépôt
</h2>


<?php if(session()->getFlashdata('error')): ?>

<p style="color:red">
<?=session()->getFlashdata('error')?>
</p>

<?php endif; ?>


<form method="post"
action="<?=site_url('client/depot/valider')?>">


<?=csrf_field()?>


<label>
Montant
</label>


<input 
type="number"
name="montant"
required
>


<br><br>


<button>
Valider
</button>


</form>