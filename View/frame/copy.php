
<button type="button" data-clipboard-text="www.baidu.com" class="btn btn-default btn-lg btn_copy">复制链接</button>


<script src="https://cdn.jsdelivr.net/clipboard.js/1.6.0/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        alert('复制成功');
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);

        e.clearSelection();
    });

    clipboard.on('error', function(e) {
        console.error('Action:', e.action);
        console.error('Trigger:', e.trigger);
    });
</script>