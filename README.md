# 🦄 Alfred Unicorn Busy Server

Alfred Workflow to manage status of a [unicorn-busy-server](https://github.com/estruyf/unicorn-busy-server/.

## Setup

- Requires @estruyf 's [unicorn-busy-server](https://github.com/estruyf/unicorn-busy-server/) server running on a Raspberry Pi w/ a [Pimoroni Unicorn pHAT](https://shop.pimoroni.com/products/unicorn-phat)
- Download Workflow from Releases tab and install into Alfred
- Set variables
  - `UBS_BRIGHTNESS` a float for default brightness, between `0.2`-`0.5`
  - `UBS_ADDRESS` the address of your rpi with the port and no trailing slash, example `http://192.0.0.0:5000`

## Usage

![](images/status-current.png)

Keyword trigger

- `ubs` view status
  - `⌅` to enter the change status
  - `⌘⌅` to quick change status to Busy
  - `⌥⌅` to quick change status off

Select Status

![](images/status-select.png)

![](images/status-change.png)

## Integration

Use external trigger to use workflow to control the status:

```
tell application id "com.runningwithcrayons.Alfred" to run trigger "ubs" in workflow "com.davidsword.alfredunicornbusyserver" with argument "Busy"
```

Accepts `Busy`, `Avaliable`, `Away`, `Off`

## TODO

- [ ] Brightness isn't respected at the API level
- [ ] Rainbow via this CURL doesn't work on the API, its `500`ing for me
- [ ] support for the `/api/switch` endpoint, passing in hex colours, ie `up > #c0ffee`

## Links

* https://www.eliostruyf.com/diy-building-busy-light-show-microsoft-teams-presence/
* https://davidsword.ca/slack-busy-light/
* https://github.com/estruyf/unicorn-busy-server/
