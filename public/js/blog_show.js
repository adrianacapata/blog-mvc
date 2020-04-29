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
               if (response.status === 'success') {
                   $('#add-comment').reset();
                   $('#show-comment').append('<label>' + response.author + ' at ' + response.date + '</label><p> ' + response.comment + '</p>');
               } else {
                   if (typeof response.err.global !== 'undefined') {
                        $(this).append('<span class="has-error">' + response.err.global + '</span>')
                   }
                   if (typeof response.err.author !== 'undefined') {
                       $('.author').append('<span class="has-error">' + response.err.author + '</span>');
                   }
                   if (typeof response.err.comment !== 'undefined') {
                       $('.comment').append('<span class="has-error">' + response.err.comment + '</span>');
                   }
               }
            }
       });
   });
});