# 🦄 Alfred Unicorn pHAT

Alfred Workflow to change colour of Raspberry Pi running [node-unicorn](https://github.com/davidsword/node-unicorn) to control display of [Pimoroni Unicorn pHAT](https://shop.pimoroni.com/products/unicorn-phat).

![](images/ex1.png)

- Displays standard colours for "busy", "away", and "active" status'
- Use "off" to turn off display
- Type in your own custom colours by name or a hex value

![](images/ex2.png)

Biggest potential of this workflow is the External trigger. Any other workflow or cron job, ect, can use a short applescript to change the colour of the Unicorn pHAT.

```osascript
tell application id "com.runningwithcrayons.Alfred" to run trigger "aup" in workflow "com.davidsword.alfredunicornphat" with argument "red"
```

Use `:aup` to view state in a browser. 

See code for inline @TODOs of improvements.
