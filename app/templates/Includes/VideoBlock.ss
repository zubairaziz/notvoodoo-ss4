<% with Data %>
  <div class="video-block">
    <a href="$URL" class="video-block__inner" data-modal="video" aria-label="$Title">
      <% if Thumbnail.Exists %>
        <img src="$Thumbnail.FocusFill(515, 300).URL" alt="$Title">
      <% end_if %>

      $AssetIcon('play-button')
    </a>

    <% if Title %>
      <div class="video-block__title">$Title</div>
    <% end_if %>

    <% if LeadIn %>
      <div class="video-block__lead-in">$LeadIn</div>
    <% end_if %>
  </div>
<% end_with %>
