// モーダルウィンドウ処理
$(function () {
    $('.js-open').click(function () {
        let $this = $(this);
        content = $this.data('id');
        // $('.modal-window').children('ngname').html('<p data-id="' + content + '">ID:' + content + '</p>');
        $('.modal-window').children('.ngname').attr('data-id', content).text('ID:' + content);        
        $('#overlay, .modal-window').fadeIn();
    });
    
    $('.js-close, #overlay').click(function () {
        $('#overlay, .modal-window').fadeOut();
    });
    // リプライのPC番ウィンドウ閉じるとき
    // $(document).on('click touchend', function(event){
    //     if(!$(event.target).closest('.rep-window, .reppopdone').length){
    //         $('.rep-window').fadeOut();
    //     }
    // });
  });
