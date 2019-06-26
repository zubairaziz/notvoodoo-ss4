<div id="$HolderID" class="field<% if $extraClass %> $extraClass<% end_if %>">
  <% if $Title %><label for="$ID">$Title<% if Required || IsRequiredPasswordField %> <sup>*</sup><% end_if %></label><% end_if %>
  <div class="field__wrapper">
    $Field
  </div>
  <% if $RightTitle %><label class="right" for="$ID">$RightTitle</label><% end_if %>
  <% if $Message %><span class="message $MessageType">$Message</span><% end_if %>
  <% if $Description %><span class="description">$Description</span><% end_if %>
</div>
