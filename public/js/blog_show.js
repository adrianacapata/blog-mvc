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
       $('.has-error').remove();
       //serialize input data and send it to validation function
       let formData = $(this).serialize();

       $.ajax({
           url: '/comment/add',
           type: 'post',
           data: formData,
           dataType: 'json',

           success: function (response) {
               $('#add-comment').reset();
               $('#show-comment').append('<label>' + response.author + ' at ' + response.date + '</label><p> ' + response.comment + '</p>');
               },
           error: function (request) {
                if (request.responseJSON.errors.author !== undefined){
                    $('.author').append('<span class="has-error">' + request.responseJSON.errors.author + '</span>');
                }
               if (request.responseJSON.errors.comment !== undefined){
                   $('.comment').append('<span class="has-error">' + request.responseJSON.errors.comment + '</span>');
               }
               if (request.responseJSON.errors.global !== undefined) {
                   $('#add-comment').append('<span class="has-error">' + request.responseJSON.errors.global + '</span>')
               }
           },
       });
   });
});