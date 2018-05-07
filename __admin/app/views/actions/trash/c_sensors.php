<?php

if (!defined("_VALID_PHP")) {die();}

include 'actions/langs/sensors_ru.lang.php';
require_once 'actions/plugins/sensors.class.php';
sensors::$db = $pdo;

if (isset($_POST['save'])) {
	sensors::register();
}

?>

<div class="content-box">
	<div class="content-box-header">
		<h3><?php print $sensors_lang['register_sensors']; ?></h3>
        <div class="clear"></div>
    </div>

	<div class="content-box-content">
        <form action="" method="post">
            <div>
				<div>
					<span style="font-weight: bold; padding: 0 0 10px;"><?php print $sensors_lang['sensors']; ?>:</span>
					<div style="border: 1px solid #d5d5d5; border-radius: 5px; width: 50%; height: 240px; padding: 10px 0 10px 10px; margin-top: 10px;">
						<div style="background: #fff; height: 240px; overflow: auto;">
							<?php
								$sensors = sensors::getSensorsRemote();
								$sensors_registered = sensors::getSensorsRegistered();
								//print "<pre>\nRemote sensors:\n".var_export($sensors, true)."\n\nRegistered sensors:\n".var_export($sensors_registered, true)."\n\n</pre>";
								if (is_array($sensors) && count($sensors)) {
									foreach ($sensors as $sensor) {
										$is_registered = isset($sensors_registered[$sensor['MachineNumber']]['id']);
										print '<p>
											№'.$sensor['MachineNumber'].' '.$sensor['MachineAlias'].' ('.$sensor['IP'].') 
											<select name="direction['.$sensor['MachineNumber'].']" style="width: 300px;">
												<option value="in">'.$sensors_lang['in'].' &darr;</option>
												<option value="out"'.(($is_registered && ($sensors_registered[$sensor['MachineNumber']]['direction']=='out'))? ' selected="selected"': '').'>'.$sensors_lang['out'].' &uarr;</option>
											</select>
										</p>';
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>

            <div style="padding-top: 20px;">
                <p><input type="submit" name="save" value="<?php print $admin_lang['save']; ?>" class="button" /></p>
                <!-- <p><input type="reset" name="reset" value="<?php print $admin_lang['reset']; ?>" class="button" /></p> -->
            </div>
        </form>
        <div class="clear"></div>
	</div>
</div>