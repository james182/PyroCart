

  <div class="body_mid_box">
    <div class="body_mid_left_box_cont">
      <div class="box_left_bg"></div>
      <div class="box_mid_bg">
        <div class="body_mid_list">
          <ul>
           <li><a  href="<?php echo base_url();?>index.php/memberhome/usersettings">Users Settings</a></li>
            <li><a href="<?php echo base_url();?>index.php/memberhome/current_newsltter">Current Newsletter</a></li>
            <li><a href="<?php echo base_url();?>index.php/memberhome/help_desk">Help desk</a></li>
            <li><a href="<?php echo base_url();?>index.php/memberhome/special_offer">Specisl Offer</a></li>
          </ul>
        </div>
      </div>
      <div class="box_right_bg"></div>
      <div class="clear"></div>
    </div>
    <div class="body_mid_right_box">
      <div class="flash_bg_top"></div>
      <div class="flash_bg_midel"> <br>
<br>
		<center>
		<font size=3 color=black face=Verdana><b>Recurring Payments Profile Details</b></font>
		<br><br>

	<table width=400>
		<?php
    		foreach($data as $key => $value) {

    			echo "<tr><td>$key:</td><td>$value</td>";
    		}
    	?>
	</table>

<input type="button" name="back" value="Back" onclick="history.back()"/>
</center>
 <div class="clear"></div>
      </div>
      <div class="flash_bg_bottom"></div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="clear"></div>