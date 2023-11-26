$(function () {
    var box = $('.n5qj_ancd .ancd_hcxx');
    $('.n5qj_ancd').click(function () {
		if($(".ancd_hcxx").css("display") != "none") {
			box.stop().hide(150);
		} else {
			box.stop().show(300);
		}
        
    });
	$('.n5qj_ancd').hover(function () {},function () {
		box.stop().hide(150);
	})
})

$(function () {
    var box = document.getElementById('fd');
    box.onmousedown = function (event) {
        var e = event || window.event,
  t = e.target || e.srcElement,
  x1 = e.clientX,
  y1 = e.clientY,
  dragLeft = this.offsetLeft,
  dragTop = this.offsetTop;
        document.onmousemove = function (event) {
            var e = event || window.event,
  t = e.target || e.srcElement,
  x2 = e.clientX,
  y2 = e.clientY,
  x = x2 - x1,
  y = y2 - y1;
            box.style.left = (dragLeft + x) + 'px';
            box.style.top = (dragTop + y) + 'px';
        }
        document.onmouseup = function () {
            this.onmousemove = null;
        }
    }
})