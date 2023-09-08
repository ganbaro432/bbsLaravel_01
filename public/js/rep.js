// 返信マークリンク化
    var textMsg = document.getElementById('commentbody');
    textMsg.innerHTML=textMsg.innerHTML.replace(/(&gt;&gt;)([0-9]+)/g,"<button class='reppop' data-serial='$2'>$1$2</button>");
