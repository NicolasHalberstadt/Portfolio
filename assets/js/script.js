document.addEventListener("DOMContentLoaded", function (event) {
    var cursor = document.querySelector("#cursor");
    var initCursor = false;

    window.onmousemove = function (e) {
        var mouseX = e.clientX;
        var mouseY = e.clientY;

        if (!initCursor) {
            // cursor.style.opacity = 1;
            TweenLite.to(cursor, 0.3, {
                opacity: 1
            });
            initCursor = true;
        }

        TweenLite.to(cursor, 0, {
            top: mouseY + "px",
            left: mouseX + "px"
        });
    };

    window.onmouseout = function (e) {
        TweenLite.to(cursor, 0.3, {
            opacity: 0
        });
        initCursor = false;
    };
});