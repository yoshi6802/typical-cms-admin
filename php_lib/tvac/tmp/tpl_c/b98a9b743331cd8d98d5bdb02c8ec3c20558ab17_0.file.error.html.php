<?php
/* Smarty version 3.1.28, created on 2017-01-25 12:15:09
  from "C:\xampp\htdocs\www\php_lib\tvac\tpl\error.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5888183d99fb25_92190977',
  'file_dependency' => 
  array (
    'b98a9b743331cd8d98d5bdb02c8ec3c20558ab17' => 
    array (
      0 => 'C:\\xampp\\htdocs\\www\\php_lib\\tvac\\tpl\\error.html',
      1 => 1484037434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5888183d99fb25_92190977 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>システムエラー | <?php if (isset($_smarty_tpl->tpl_vars['meta_title']->value)) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['meta_title']->value, ENT_QUOTES, 'UTF-8', true);
}?></title>
    </head>
    <body>

        <div id="container">
            <h1>システムエラー</h1>
            <ul>
                <?php if (isset($_smarty_tpl->tpl_vars['errorMessage']->value)) {?>
                    <li style="color:red"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['errorMessage']->value, ENT_QUOTES, 'UTF-8', true);?>
</li>
                <?php } elseif (isset($_smarty_tpl->tpl_vars['error']->value)) {?>
                    <?php
$_from = $_smarty_tpl->tpl_vars['error']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_item_0_saved_item = isset($_smarty_tpl->tpl_vars['item']) ? $_smarty_tpl->tpl_vars['item'] : false;
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable();
$__foreach_item_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_item_0_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$__foreach_item_0_saved_local_item = $_smarty_tpl->tpl_vars['item'];
?>
                        <li><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['item']->value["lawer_name"], ENT_QUOTES, 'UTF-8', true);?>
</li>
                    <?php
$_smarty_tpl->tpl_vars['item'] = $__foreach_item_0_saved_local_item;
}
} else {
?>
                        <li>原因不明のエラーです。</li>
                    <?php
}
if ($__foreach_item_0_saved_item) {
$_smarty_tpl->tpl_vars['item'] = $__foreach_item_0_saved_item;
}
?>
                <?php } else { ?>
                    <li>原因不明のエラーです。</li>
                <?php }?>
            </ul>
        </div>
    </body>
</html>
<?php }
}
