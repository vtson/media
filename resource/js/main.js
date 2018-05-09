$(function () {
    var DataGlobal = {};

    //````````` Dropdown```
    var dropdown = document.querySelector('.dropdown');
    dropdown.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdown.classList.toggle('is-active');
    });
    //``````` End Dropdown````````

    //````` Tab Post ````````
    $('#tabs li').on('click', function () {
        var tabId = $(this).attr("id");
        $('#tabs li').removeClass("is-active");
        $(this).addClass("is-active");

        document.getElementById(tabId).addEventListener("click", function () {
            $.get("http://media.local:8080/views/posts/partitial/post-" + tabId + ".php", function (data) {
                $("#tabs-content").empty().html(data);
                $('.reviewVideoUpload').attr('hidden', true);
                $('.reviewImageUpload').attr('hidden', true);
            });
        });

    });
    //`````` End Tab Post ```````````

    //```````` Play and Pause Video On Scroll ```````
    $(document).on('scroll', checkMedia);
    //```````` End Scroll ````````````

// `````````` Comment ````````
    function PhpComment(element) {
        this.element = element;
        this.init();
    }

    PhpComment.prototype.init = function () {
        this.setupVariables();
        this.setupEvents();
    }

    PhpComment.prototype.setupVariables = function () {
        this.commentForm = this.element.find(".comment-form");
        this.bodyField = this.element.find("#comment-content");
        this.cardPost = this.element.find(".card");
    }

    PhpComment.prototype.setupEvents = function () {
        var phpComment = this,
            newMedia;

        $(document).on("click", ".reply-link", function (e) {
            e.preventDefault();
            var media = $(this).closest(".media-parent");
            media.find(">.media-content").append(phpComment.commentForm);
            DataGlobal.to_user_id = $(this).closest(".media-content").attr("data-user-id");
        });

        phpComment.commentForm.on("submit", function (e) {
            e.preventDefault();
            var parent_id,
                content = phpComment.bodyField.val(),
                post_id = phpComment.cardPost.attr("data-post-id");

            if (phpComment.commentForm.parents(".media-content").length > 0) {
                parent_id = phpComment.commentForm.closest(".media-content").attr("data-comment-id");
            }

            if ($(this).find('textarea').val().trim() === '')
                return false;

            $.ajax({
                url: phpComment.commentForm.attr("action"),
                method: 'POST',
                dataType: 'json',
                data: {post_id: post_id, content: content, parent_id: parent_id, to_user_id: DataGlobal.to_user_id},
                success: function (data) {
                    if (data.errorLogin) {
                        alert(data.errorLogin);
                        window.open("/users/login");
                        return;
                    }
                    if (data.errorComment) {
                        alert(data.errorComment);
                        return;
                    }
                    newMedia = data;
                    phpComment.commentForm.before(newMedia);
                    phpComment.bodyField.val("");
                }
            });
        });

    }

    $.fn.phpComment = function (options) {
        new PhpComment(this);
        return this;
    }

    $(".comments").phpComment();
    //````````` End comment ````````

    //`````` Like `````````````
    $(".like").click(function (e) {

        e.preventDefault();
        var object = $(this).attr("data-object"),
            post_id,
            comment_id;
            thisLike = $(this);
        if (object === 'post') {
            post_id = thisLike.closest(".card").attr("data-post-id");
        } else if (object === 'comment') {
            comment_id = thisLike.closest(".media-content").attr("data-comment-id");
        }

        if (thisLike.hasClass('like-on')) {
            action = 'unlike';
        } else if (thisLike.hasClass('like-off')) {
            action = 'like';
        }
        $.ajax({
            method: 'POST',
            url: "http://media.local:8080/like/action",
            data: {comment_id: comment_id, post_id: post_id, action: action, object: object},
            dataType: 'json',
            success: function (data) {
                if (data.action === "like") {
                    thisLike.removeClass('like-off').addClass('like-on');
                    thisLike.text(data.total +' Like');
                } else if (data.action === "unlike") {
                    thisLike.removeClass('like-on').addClass('like-off');
                    thisLike.text(data.total +' Like');
                }
            }
        });
    });
    // ``````````````End Like


});

function checkMedia() {
    var media = $('video').not("[autoplay='autoplay']");
    var tolerancePixel = 0;

    var scrollTop = $(window).scrollTop() + tolerancePixel;
    var scrollBottom = $(window).scrollTop() + $(window).height() - tolerancePixel;

    media.each(function (index, el) {
        var yTopMedia = $(this).offset().top;
        var yBottomMedia = $(this).height() + yTopMedia;

        if (scrollTop < yBottomMedia && scrollBottom > yTopMedia) {
            $(this).get(0).play();
        } else {
            $(this).get(0).pause();
        }
    });
}