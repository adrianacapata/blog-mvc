$(document).ready(function () {
   $('.dislike').on('click', function () {
       let blogId = $(this).data('id');

       $.ajax({
           url: '/blog/dislike',
           type: 'get',
           data: {
               'blog_id': blogId
           },
           dataType:'json',
           success: function (response) {
               if (response.status === 'success') {
                   let dislikeCount = $('#dislike-count').text();
                   $('#dislike-count').text(parseInt(dislikeCount) + 1);
               }
           }
       });
   });

   $('.like').on('click', function () {
       let blogId = $(this).data('id');

       $.ajax({
           url: '/blog/like',
           type: 'get',
           data: {
               'blog_id': blogId
           },
           dataType:'json',
           success: function (response) {
               if (response.status === 'success') {
                   let likeCount = $('#like-count').text();
                   $('#like-count').text(parseInt(likeCount) + 1);
               }
           }
       });
   });

   $('#add-comment').on('submit', function (event) {
       event.preventDefault();
       let formData = $('#add-comment').serialize();

       $.ajax({
           url: '/comment/add',
           type: 'post',
           data: formData,
           dataType: 'json',

           success: function (response) {
               if (response.status === 'success') {
                   $('#add-comment')[0].reset();
                   $('#show-comment').append('<label>' + response.author + ' at ' + response.date + '</label><p> ' + response.comment + '</p>');
               }
           }
       });
   });
});