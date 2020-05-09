# 🦄 Alfred Unicorn pHAT

Alfred Workflow to change colour of a Raspberry Pi's [Pimoroni Unicorn pHAT](https://shop.pimoroni.com/products/unicorn-phat) running [node-unicorn](https://github.com/davidsword/node-unicorn).

## Setup

- Requires [node-unicorn](https://github.com/davidsword/node-unicorn) server running on a Raspberry Pi w/ a [Pimoroni Unicorn pHAT](https://shop.pimoroni.com/products/unicorn-phat)
- Download Workflow from [Releases](https://github.com/davidsword/alfred-unicorn-phat/releases) tab and install into Alfred
- Set Workflow variables 
    - `RPI_URL` with no trailing slash to RPI, ex `http://192.168.0.0:3001`
    - `BRIGHTNESS` default value, where `100`=brightest,`1`=darkest, ex `50`


## Usage

- `uph` keyword to list & display standard colours for "busy", "away", and "active" status'
- `uph pink` type in your own custom colours by name or a hex value
- `uph c0ff33` or a hex value
- `uph purple 50` add an interger to set brightness (`100` brightest, `1` darkest)
- `uph off` to turn off display

Biggest potential of this workflow is the External trigger. Any other workflow or cron job, ect, can use a short applescript to change the colour of the Unicorn pHAT.

```osascript
tell application id "com.runningwithcrayons.Alfred" to run trigger "uph" in workflow "com.davidsword.alfredunicornphat" with argument "red 50"
```

## RoadMap

See inline `@TODO`s of improvements.
