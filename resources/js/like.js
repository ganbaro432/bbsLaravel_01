$( function ()
{
    let likeReviewId;
    $('body').on('click', '.like-toggle', function(){
        let $this = $(this);
        likeReviewId = $this.data('review-id');
        $this.prop('disabled', true);
        $this.next('button').prop('disabled', true);

        $.ajax({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: '/like',
            method: 'POST',
            data: {
                'review_id': likeReviewId
            },
        })
        
        .done(function (data){
            if(data.review_likes_count !== null){
                $this.toggleClass('liked');
                $this.children('.like-counter').html(data.review_likes_count);
            }
        })

        .fail(function(){
            console.log('fail');
        });
    });
} );

//badボタン
$( function ()
{
    let likeReviewId;
    $('body').on('click', '.dislike-toggle', function(){
        let $this = $(this);
        likeReviewId = $this.data('review-id');
        $this.prop('disabled', true);
        $this.prev('button').prop('disabled', true);
        $.ajax({
            headers:{
                'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            },
            url: '/dislike',
            method: 'POST',
            data: {
                'review_id': likeReviewId
            },
        })
        
        .done(function (data){
            // nullのときは何もしたくない
            if(data.review_dislikes_count !== null){
            $this.toggleClass('disliked');
            // $this.children('.dislike-counter').html(data.review_dislikes_count);
            }
        })

        .fail(function(){
            console.log('fail');
        });
    });
} ); 