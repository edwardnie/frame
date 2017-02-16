</div>

<script src="<?php echo Helper_Url::themes('js/function.js') ?>"></script>
<script>
    $(window).load(function () {
        window.setTimeout(function () {
            $('#ajax-loader').fadeOut();
        }, 100);
    });
    $("#close-box").hide();
    $("#close-box").click(function () {
        hideBox("addBox");
    })
</script>
</body>
</html>