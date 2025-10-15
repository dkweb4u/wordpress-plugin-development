<div class="wp_crud_emp_plugin">
<!-- add form -->
<div class="add_emp_form d-none">
      <h1>Add Employee</h1>
  <form id="wpEmpForm" enctype="multipart/form-data">
<p>
<label for="name">Emp Name</label>
<input type="text" name="name" id="name" required>
</p>
<p>
<label for="email">Emp Email</label>
<input type="email" name="email" id="email" required>
</p>
<p>
<label for="designation">Emp Designation</label>
<select name="designation" id="designation"  required>
    <option value="">----Select Designation----</option>
    <option value="wordpress">Wordpress Developer</option>
    <option value="fullstack">Full stack Developer</option>
    <option value="java">Java Developer</option>
    <option value="php">PHP Developer</option>

</select>
</p>
<p>
<label for="profile">Emp Profile</label>
<input type="file" name="profile" id="profile" >
</p>
<input type="hidden" name="action" value="wp_emp_plugin_action">
<?php wp_nonce_field('wp_emp_plugin_action', 'wp_emp_nonce'); ?>
<button type="submit">Submit</button>

</form>
</div>
<!-- Edit form -->
<div class="edit_emp_form d-none">
      <h1>Edit Employee</h1>
  <form id="editEmpForm" enctype="multipart/form-data">
<p>
<label for="name">Emp Name</label>
<input type="text" name="name" id="name" required>
</p>
<p>
<label for="email">Emp Email</label>
<input type="email" name="email" id="email" required>
</p>
<p>
<label for="designation">Emp Designation</label>
<select name="designation" id="designation"  required>
    <option value="">----Select Designation----</option>
    <option value="wordpress">Wordpress Developer</option>
    <option value="fullstack">Full stack Developer</option>
    <option value="java">Java Developer</option>
    <option value="php">PHP Developer</option>

</select>
</p>
<p>
<label for="profile">Emp Profile</label>
<input type="file" name="profile" id="profile" >
<input type="hidden" name="emp_id" id="emp_id">
</p>
<input type="hidden" name="action" value="wp_emp_plugin_edit_action">
<?php wp_nonce_field('wp_emp_plugin_edit_action', 'wp_emp_nonce'); ?>
<button type="submit">Update</button>

</form>
</div>


<br>
<hr>
<br>
  <button class="add_new_emp_btn add_new_btn" >Add New</button>
<br>

<h3>Emp List</h3>

<table>
   <thead>
    <th>#ID</th>
    <th>#Name</th>
    <th>#Email</th>
    <th>#Dsignation</th>
    <th>#Profile</th>
    <th>#Action</th>

   </thead>
   <tbody id="table_emp_data">
    <tr>
      <td colspan="10" style="text-align: center;">Loading...</td>
    </tr>
   </tbody>
</table>
</div>