function add_capacitor_pull_to_refresh() {
// Load PullToRefresh library from CDN
wp_enqueue_script(
'pulltorefresh',
'https://cdnjs.cloudflare.com/ajax/libs/pulltorefreshjs/0.1.22/index.umd.min.js',
array(),
null,
true
);

// Initialize the script
wp_add_inline_script('pulltorefresh', "
document.addEventListener('DOMContentLoaded', function() {
PullToRefresh.init({
mainElement: 'body',
onRefresh: function() {
window.location.reload();
}
});
});
");
}
add_action('wp_enqueue_scripts', 'add_capacitor_pull_to_refresh');