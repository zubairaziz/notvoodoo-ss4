<% if Items.Count %>
  <ul class="<% if ExtraClass %>$ExtraClass <% end_if %>" data-paginated-list>
    $ResultsList
  </ul>
<% else %>
  <div class="search-empty-results">
    No results found
  </div>
<% end_if %>

<% if Items.MoreThanOnePage %>
  <% with Items %>
    <% include PaginatedListNav %>
  <% end_with %>
<% end_if %>
