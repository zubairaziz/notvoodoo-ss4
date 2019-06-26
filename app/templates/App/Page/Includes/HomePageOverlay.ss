<div class="$ContainerClass modal-content-wrapper">
  <div class="homepage-overlay">
    <% if IncludeVideo %>
      <div class="homepage-overlay__video">
        <iframe src="$VideoURL?rel=0" frameborder="0" allowfullscreen></iframe>
      </div>
    <% end_if %>

    <% if IncludeImage && Image.Exists %>
      <% if IncludeImageLink %>
        <% if ImageLinkType = 'Internal' %>
          <a class="homepage-overlay__image" href="$ImageInternalLink.Link">
        <% end_if %>

        <% if ImageLinkType = 'External' %>
          <a class="homepage-overlay__image" href="$ImageExternalLink" target="_blank">
        <% end_if %>

        <% if ImageLinkType = 'File' %>
          <a class="homepage-overlay__image" href="$ImageFile.Link" target="_blank">
        <% end_if %>
      <% else %>
        <div class="homepage-overlay__image">
      <% end_if %>

      $Image

      <% if IncludeImageLink %>
        </a>
      <% else %>
        </div>
      <% end_if %>
    <% end_if %>

    <% if IncludeContent %>
      <div class="homepage-overlay__content">
        $Content

        <% if CTAs %>
          <ul class="homepage-overlay__ctas">
            <% loop CTAs %>
              <li>
                <% include CallToAction %>
              </li>
            <% end_loop %>
          </ul>
        <% end_if %>
      </div>
    <% end_if %>
  </div>
</div>
