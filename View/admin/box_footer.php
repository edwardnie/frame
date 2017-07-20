</div>

<script src="<?php echo Helper_Url::themes('js/function.js') ?>"></script>


<script>
    $(window).load(function () {
        window.setTimeout(function () {
            $('#ajax-loader').fadeOut();
        }, 100);
    });

    $(".cancel").click(function (  ) {
        parent.hideBox("addBox");
        return false;
    });
</script>
</body>
</html>