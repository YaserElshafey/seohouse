<?php
// LOCAL TEST ONLY: stand-in for a Calendly inline page (separate origin, port 8091).
// Shows MOCK slots; "Confirm" posts Calendly's event_scheduled message shape to the parent.
$ref = preg_replace( '/[^a-f0-9]/', '', $_GET['utm_content'] ?? '' );
header( 'Content-Type: text/html; charset=utf-8' );
?><!doctype html><html><body style="font:15px sans-serif;margin:0;padding:16px">
<h3 id="t">MOCK Calendly — 30 Minute Meeting</h3>
<p id="q">name=<?php echo htmlspecialchars( $_GET['name'] ?? '' ); ?> | email=<?php echo htmlspecialchars( $_GET['email'] ?? '' ); ?> | ref=<?php echo $ref; ?></p>
<p>Time zone: <span id="tz"></span></p>
<div><button data-slot="2026-10-08T11:00:00Z">Thu 8 Oct 11:00</button> <button data-slot="2026-10-08T11:30:00Z">Thu 8 Oct 11:30</button></div>
<p><button id="confirm" disabled>Schedule Event</button></p>
<p id="done" hidden>You are scheduled (mock)</p>
<script>
document.getElementById('tz').textContent = Intl.DateTimeFormat().resolvedOptions().timeZone;
var pick = null;
document.querySelectorAll('[data-slot]').forEach(function (b) { b.onclick = function () { pick = b.dataset.slot; document.getElementById('confirm').disabled = false; }; });
parent.postMessage({ event: 'calendly.page_height', payload: { height: '720px' } }, '*');
document.getElementById('confirm').onclick = function () {
  var ev = 'https://api.calendly.com/scheduled_events/EVT-<?php echo $ref; ?>';
  parent.postMessage({ event: 'calendly.event_scheduled', payload: { event: { uri: ev }, invitee: { uri: ev + '/invitees/INV-<?php echo $ref; ?>' } } }, '*');
  document.getElementById('done').hidden = false;
};
</script></body></html>
