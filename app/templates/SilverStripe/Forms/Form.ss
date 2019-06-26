<form $AttributesHTML autocomplete="off" novalidate>
  <% if Message && MessageType == 'good' %>
    <div class="form-session-message">
      $Message
    </div>
  <% end_if %>

  <fieldset>
    <div class="grid-x grid-margin-x">
      <% loop Fields %>
        <div class="cell">
          $FieldHolder
        </div>
      <% end_loop %>
    </div>
  </fieldset>

  <div class="form-error-message">
    <% if Message && MessageType == 'bad' %>$Message<% end_if %>
  </div>

  <% if $Actions %>
    <div class="btn-toolbar">
      <% loop $Actions %>
        $Field
      <% end_loop %>
    </div>
  <% end_if %>
</form>
