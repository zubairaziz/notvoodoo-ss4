<div data-paginated-list-nav data-aos="fade-up">
  <div class="paginated-count">
    <strong>$LastItem</strong> of $TotalItems
  </div>

  <div class="btn-wrapper">
    <% if NotLastPage %>
      <a href="$NextLink" class="btn btn--fill" data-paginated-list-trigger>Load More</a>
    <% end_if %>

    <% if NotFirstPage %>
      <a href="$FirstLink" class="btn" data-paginated-list-trigger data-paginated-list-hide>Show Less</a>
    <% end_if %>
  </div>
</div>
