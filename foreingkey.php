<?php $bdd = new PDO('mysql:host=localhost;dbname=dcw_touring;charset=utf8','root','');
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$req = $bdd->prepare('SELECT id_hotel, name FROM hotels');
	$req->execute();
?>
<form action="" method="POST">
	<input list="dcw_Table" type="text" name="id">
	<datalist id="dcw_Table">
	<?php
		while($i=$req->fetch()) {
			echo "<option value='".$i['id_hotel']."'>" . $i['name'] . "</option>" ;
			}
	?>
	</datalist>
	<input type="submit" name="btn">
	<?php
		try{
			if (isset($_POST['id']) and !empty($_POST['id'])) {
				$id = htmlspecialchars($_POST['id']);
			
				$req = $bdd->prepare('
					DELETE FROM hotels
					WHERE id_hotel = :id
					');
				$req->execute(array(
					'id'=> $id
				));
				}
			}
			catch (Exception $e){
				$req = $bdd->prepare(
					'SELECT * FROM hotels NATURAL JOIN offers WHERE id_hotel = :id;');
				$req->execute(array(
					'id'=>$id
				));
				$options = "<ul>";
				$nbOptions = 0;
				while($myHotel = $req->fetch()){

					$options .= "<li>".$myHotel['option_name']."</li>";
					$nbOptions++;
				}
				$options .= "</ul>";
				
				if ($nbOptions > 1){
					$mot1 = "les offres" ;
					$mot2 = "dépendent";
				}else{
					$mot1 = "l'offre" ;
					$mot2 = "dépend";
				}
			echo "<br><br><br>L'hôtel ".$myHotel['name']." ne peut pas être supprimé, car ".$mot1." ci-dessous en ".$mot2." : ".$options;

			}
	?>
</form>