<h1>$Subject</h1>

<dl>
  <% loop Fields %>
    <dt style="font-weight: bold">$DisplayName</dt>
    <dd style="margin: 0 0 15px 0">$Value</dd>
  <% end_loop %>

  <hr>

  <dt style="font-weight: bold">Submit Date</dt>
  <dd style="margin: 0 0 15px 0">$Submission.Created.Nice</dd>
</dl>
