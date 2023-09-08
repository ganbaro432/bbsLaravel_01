$(function () {
    // $('.js-repform').click(function () {
    $('body').on('click', '.js-repform', function () {        
        let $this = $(this);
        content = $this.data('repserial');
        repid = $this.data('repid');

        $('.com-form').insertAfter($this.parent());
        $('.com-form').find('textarea').text('>>' + content + '\r');
        $('.com-form').find('.rep_rep_id').attr('value', repid);
        $('.com-form').fadeIn();
    });

    $('.close-rep').click(function () {
        let $this = $(this);
        $this.prevAll('.js-repform').fadeIn();
        $('.com-form').hide();
    });
  });

//   返信リストを表示
$(function () {
    $('.js-replist').click(function () {
        let $this = $(this);
        $this.parent('div').next('.reparea').toggle();
        reptarget = $this.data('reptarget');

        $.ajax({
            type: 'GET',
            url: '/repshow',
            data: {
                'reptarget': reptarget
            },
        })

        .done(function (data){
            // 取得したデータを配置
            let html ='';
            $.each(data, function(index, value){
                let id = value.id;
                let user_id = value.user_id;
                let name = value.name;
                let comment_serial = value.comment_serial;
                let body = value.body;
                let like_count = value.like_count;
                let dislike_count = value.dislike_count;
                // 日付
                let created_at = value.created_at;
                let date = new Date(created_at);
                // let year = date.getFullYear();
                let month = ('00' + (date.getMonth() + 1)).slice(-2);
                let day = ('00' + date.getDate()).slice(-2);
                let hour = date.getHours();
                let minute = date.getMinutes();
                let viewdate = month + '/' + day + '\t' + hour +':' + minute;
                // <button class="dislike-toggle" data-review-id="${id}"><i class="fa-solid fa-thumbs-down relative top-1 mr-1 text-md"></i><span class="dislike-counter text-md">${dislike_count}</span></button>
                html += `
                        <div class="border-b px-5 pb-2 pt-2 {{$comment->user_id}}">
                        <div class="flex justify-between text-xs text-gray-500 mb-3">
                        <div class="">
                        <span>${comment_serial}</span>
                        <span>${name}</span>&nbsp;${viewdate} ID:${user_id}&nbsp;
                        </div>
                        </div>
                        <div class="thcombody font-normal text-md text-gray-700 mb-1 mt-3">${body}</div>   
                        <div class="py-4 mb-1 flex text-sm">  
                        <div class="flex text-gray-400 items-center mr-3">
                        <button class="like-toggle mr-2" data-review-id="${id}"><i class="fa-solid fa-thumbs-up text-md mr-1"></i><span class="like-counter text-md mr-1">${like_count}</span></button>
                         
                        </div>
                        <button class="js-repform text-gray-400" data-repserial="${comment_serial}" data-repid="${id}"><i class="fa-solid fa-reply"></i></button>
                        </div>
                        </div>

                    `
            })
            $this.nextAll('.js-replist-close').fadeIn();
            $this.parent('div').next('.reparea').append(html);
            $this.hide();
        })
        .fail(function(){
            console.log('faile');
        })
    });
});

// 返信リスト取得後 js-replist-close
$( function (){
    $('.js-replist-done').click(function(){
        let $this = $(this);
        $this.parent('div').next('.reparea').toggle();
        if($this.parent('div').next('.reparea').css('display') == 'block'){
            $this.hide();
            $this.next('.js-replist-close').fadeIn();
        }
    });

    $('.js-replist-close').click(function(){
        let $this = $(this);
        $this.parent('div').next('.reparea').toggle();
        if($this.parent('div').next('.reparea').css('display') == 'none'){
            $this.hide();
            $this.prev('.js-replist-done').fadeIn();
        }
    })
});

// ポップアップで返信表示
// ポップアップ
$(function (){
    $('body').on('click', '.reppop', function(){
        let $this = $(this);
        serial = $this.data('serial');
        threadid = $('#headerarea').data('threadid');
        id = $this.parent('.thcombody').data('commentid');
        
        // 位置
        let os = $this.offset();
        let top = os.top;

        $.ajax({
            type: 'GET',
            url: '/reppop',
            data: {
                'serial': serial,
                'threadid': threadid
            },
        })
        .done(function(data){
            
            if(data.user_id == null){
            // 返信先ウィンドウは個別に持つ必要がある
            $this.parent().next('.rep-window').children('.replyid').html('<p>なし</p>');        

            $this.clone(false).insertAfter($this).removeClass('reppop').addClass('reppopnull');
            $this.hide();
            return;                
            };
            let html ='';
            let user_id = data.user_id;
            let name = data.name;
            let comment_serial = data.comment_serial;
            let body = data.body;
            // 日付
            let created_at = data.created_at;
            let date = new Date(created_at);
            let month = ('00' + (date.getMonth() + 1)).slice(-2);
            let day = ('00' + date.getDate()).slice(-2);
            let hour = date.getHours();
            let minute = date.getMinutes();
            let viewdate = month + '/' + day + '\t' + hour +':' + minute;

            // html = `

            //     <div class="flex justify-between text-xs text-gray-500 mb-1">
            //     <div class="">
            //     <span>${comment_serial}</span>
            //     <span>${name}</span>&nbsp;${viewdate} ID:${user_id}&nbsp;
            //     </div>
            //     </div>
            //     <div class="thcombody font-normal text-md text-gray-700 mb-1 mt-3">${body}</div>   
            //     <div class="py-4 mb-1 flex text-sm">  
            //     <div class="flex text-gray-400 items-center mr-3">
                
            //     </div>

            // `;
            
            html2 = `
            <div class="rep-window${comment_serial} repcontent">
            <div class="replyid">
            <div class="flex justify-between text-xs text-gray-500 mb-1">
            <div class="">
            <span>${comment_serial}</span>
            <span>${name}</span>&nbsp;${viewdate} ID:${user_id}&nbsp;
            </div>
            </div>
            <div class="thcombody font-normal text-md text-gray-700 mb-1 mt-3">${body}</div>   
            <div class="py-4 mb-1 flex text-sm">  
            <div class="flex text-gray-400 items-center mr-3">
            </div>
            </div>
            <button class="js-close button-close">閉じる</button>
            </div>                
           </span>
        `;

            // スマホとpcを判別し表示方法を変更、スマホ版
            if(window.matchMedia && window.matchMedia('(max-device-width: 640px)').matches){
                $this.parents('.thcombody').after(html2);
                variable_name = 'rep-window' + serial;
                $('#overlay').fadeIn();
                $this.parents('.thcombody').nextAll('.' + variable_name).fadeIn();
                // 返信先ウィンドウは個別に持つ必要がある
                // $this.parent().nextAll('.rep-window').children('.replyid').html(html);        
                // $('#overlay').fadeIn();
                // $this.parent().nextAll('.rep-window').fadeIn();

                // 返信先ボタンの切り替え
                $this.clone(false).insertAfter($this).removeClass('reppop').addClass('reppopdone');
                $this.hide();
            }else{
                // pc版
                // $this.parent().nextAll('.rep-window').children('.replyid').html(html); 
                // $this.parent().nextAll('.rep-window').css({
                //     position:"absolute",
                //     width:"300px",
                //     left:"30%",
                //     top:top + 10,
                //     backgroundColor:"#f9f9f9",
                //     border:"1px solid #dddddd"
                // }).fadeIn();

                // ためし
                $this.parents('.thcombody').after(html2);
                variable_name = 'rep-window' + serial;
                $this.parents('.thcombody').nextAll('.' + variable_name).css({
                    position:"absolute",
                    width:"300px",
                    left:"30%",
                    top:top + 10,
                    backgroundColor:"#f9f9f9",
                    border:"1px solid #dddddd"
                }).fadeIn();
                // 

                $this.clone(false).insertAfter($this).removeClass('reppop').addClass('reppopdone');
                $this.hide();                
                return;
            };

        })
        .fail(function(){
            console.log('fail');
            // 
            alert('ファイルの取得に失敗しました。');
            console.log("ajax通信に失敗しました");
            console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
            console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
            console.log("errorThrown    : " + errorThrown.message); // 例外情報
            console.log("URL            : " + url);            
        })
    });
    // 一度習得後の動作、ここで複数ある場合の処理もかきたい
    // $('body').on('click', '.reppopdone', function(){
    //     let $this = $(this);
    //     if(window.matchMedia && window.matchMedia('(max-device-width: 640px)').matches){
    //         $('#overlay').fadeIn();
    //         $this.parent().nextAll('.' + variable_name).fadeIn();
    //     }else{
    //         $this.parent().nextAll('.' + variable_name).fadeIn();
    //     };
    // });

    // ここに新しいポップアップを書く
    $('body').on('click', '.reppopdone', function(){
        let $this = $(this);
        serial = $this.data('serial');
        variable_name = 'rep-window' + serial;
        if(window.matchMedia && window.matchMedia('(max-device-width: 640px)').matches){
            $('#overlay').fadeIn();
            $this.parent().nextAll('.' + variable_name).fadeIn();
        }else{
            $this.parent().nextAll('.' + variable_name).fadeIn();
        };
    });

    // 閉じる
    // 新しく挿入した要素はonでないと操作できない
    $('body').on('click', '.js-close, #overlay', function(){
        $('.repcontent, #overlay').fadeOut();
    });
    // リプライのPC番ウィンドウ閉じるとき
    $(document).on('click touchend', function(event){
        if(!$(event.target).closest('.repcontent, .reppopdone').length){
            $('.repcontent').fadeOut();
        }
    });

    });

//  https://teratail.com/questions/282418 foreachで回すのが正解か 表示させるエリアをdivで固定しクラス指定して、消す専用のボタンでクローンを削除も