<h3 align="center"> Send SSRF/RCE Report Via Discord WebHook  </h4>
<p align="center">
  <a href="#installation">Installation</a> •
  <a href="#usage">Usage</a> •
  <a href="#preview">Preview</a> 

</p>

---

This Code Send Notification on Discord TextChannel When SSRF/RCE payload it's Worked .

## Installation
 Clone Repository :
```
git clone github.com/zi-gax/SSRF-WebHook
```
Add Files To Web Page Directory And Run This Code For Wirte File Permission :


```
chown www-data:www-data reports/
```

Edit WebHook url In index.php :
```
line 6 $webhookUrl 
```

## Preview

![ssrf](https://github.com/zi-gax/SSRF-WebHook/assets/67065043/bd5a2441-dda1-4d38-95ba-37470834c8ef)

## Usage

### For Example 
use link web page in payload :
```
https://site.tld/index.php
```