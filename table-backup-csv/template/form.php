<div class="wrap">
    <h3>Table Backup in CSV</h3>
<form action="" method="POST">

<?php

global $wpdb;

$tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);

?>
<select name="csv_table" id="csv_table">
  <option value="">Select Table</option>

<?php
foreach($tables as $table){
    echo ' <option value="'.$table[0].'">'.$table[0].'</option>';
}
echo '</select>';
?>

  <?php submit_button('Export Backup','primary','tbcsv_button') ?>
</form>
</div>