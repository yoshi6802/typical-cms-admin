<?php
/* Smarty version 3.1.28, created on 2017-01-25 15:07:42
  from "C:\xampp\htdocs\www\php_lib\tvac\tpl\admin_login.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_588840ae86ce79_90247243',
  'file_dependency' => 
  array (
    'a49f732e4a975d7d10e239cf569c0a21c8d247ec' => 
    array (
      0 => 'C:\\xampp\\htdocs\\www\\php_lib\\tvac\\tpl\\admin_login.html',
      1 => 1485225742,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_588840ae86ce79_90247243 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['meta_title']->value, ENT_QUOTES, 'UTF-8', true);?>
 | <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['site_title']->value, ENT_QUOTES, 'UTF-8', true);?>
</title>

    </head>
    <body>
        <h1>管理サイト　ログイン</h1>
        <?php if (isset($_smarty_tpl->tpl_vars['errors']->value)) {?>
        <?php
$_from = $_smarty_tpl->tpl_vars['errors']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_errors_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_foreach_errors']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_errors'] : false;
$__foreach_errors_0_saved_item = isset($_smarty_tpl->tpl_vars['item']) ? $_smarty_tpl->tpl_vars['item'] : false;
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable();
$__foreach_errors_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
$_smarty_tpl->tpl_vars['__smarty_foreach_errors'] = new Smarty_Variable(array());
if ($__foreach_errors_0_total) {
$__foreach_errors_0_first = true;
$__foreach_errors_0_iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$__foreach_errors_0_iteration++;
$_smarty_tpl->tpl_vars['__smarty_foreach_errors']->value['first'] = $__foreach_errors_0_first;
$_smarty_tpl->tpl_vars['__smarty_foreach_errors']->value['last'] = $__foreach_errors_0_iteration == $__foreach_errors_0_total;
$__foreach_errors_0_first = false;
$__foreach_errors_0_saved_local_item = $_smarty_tpl->tpl_vars['item'];
?>
        <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_errors']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_errors']->value['first'] : null)) {?> <p style="color:red;"> <?php }?>
        ・<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value, ENT_QUOTES, 'UTF-8', true);?>
<br>
        <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_errors']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_errors']->value['last'] : null)) {?></p><?php }?>
        <?php
$_smarty_tpl->tpl_vars['item'] = $__foreach_errors_0_saved_local_item;
}
}
if ($__foreach_errors_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_foreach_errors'] = $__foreach_errors_0_saved;
}
if ($__foreach_errors_0_saved_item) {
$_smarty_tpl->tpl_vars['item'] = $__foreach_errors_0_saved_item;
}
?>
        <?php }?>
        <form action="" method="POST" id="form">
            <p> ユーザ名: <input type="TEXT" name="username" required max="255" id="username"> </p>
            <p> PW: <input type="PASSWORD" name="password" required max="255" id="password"> </p>
            <p> <button type="submit" >ログイン</button> </p>
        </form>
    </body>

</html>
<?php }
}
