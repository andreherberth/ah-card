<style>
    #ahcardbox {width:100%; max-width: 1168px; padding: 20px; background-color: #339900; min-height: 150px; color:white;
}
</style>

<div id="ahcardbox">
	
	<center><h3>Your GreenKardPro Card</h3></center>
	<center><p></p></center>
	<center><p>Please see your unique card number below</p></center>
	<center><p><h2><?php echo get_user_meta( get_current_user_id() , '_ah_card_number', TRUE); ?></h2></p></center>
</div>
<div id="ahpartners">
</div>