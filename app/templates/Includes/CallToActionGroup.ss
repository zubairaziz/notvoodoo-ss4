<% if Actions.Count %>
  <div class="cta-group <% if Theme == 'light' %>cta-group--light<% end_if %><% if Actions.Count > 1 %>has-multiple<% end_if %> <% if Centered %>cta-group--centered<% end_if %>" data-aos="fade-up">
    <% loop Actions %>
      <% include CallToAction %>
    <% end_loop %>
  </div>
<% end_if %>
