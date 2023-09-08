$(".openbtn").click(function () {//ボタンがクリックされたら
	$(this).toggleClass('active');//ボタン自身に activeクラスを付与し
    $("#g-nav").toggleClass('panelactive');//ナビゲーションにpanelactiveクラスを付与
    if($(this).hasClass('active')){
        $('body').css('overflow-y', 'hidden');
    } else {
        $('body').css('overflow-y','auto'); 
    }
});

$("#g-nav a").click(function () {//ナビゲーションのリンクがクリックされたら
    $(".openbtn").removeClass('active');//ボタンの activeクラスを除去し
    $("#g-nav").removeClass('panelactive');//ナビゲーションのpanelactiveクラスも除去
    if($(".openbtn").hasClass('active')){
        $('body').css('overflow-y', 'hidden');
    } else {
        $('body').css('overflow-y','auto'); 
    }
});