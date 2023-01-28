## Getting Started

First you need to install the theme on your wordpress installation.

After that you need to replace `teste.local` with your website url like shown below.

```
{
  "scripts": {
    "sync": "browser-sync start -p 'teste.local' --files '**/*.php' 'build/*.js' 'build/*.css'",
  }
}
```

Then you need to install all the dependencies using the following command:

```bash
npm install
# or
yarn
```

After that you just need to execute the following command:

```bash
npm run preview
# or
yarn preview
```

After you have finished your theme, in order to export it to your live site, execute the following command:

```bash
npm run build
# or
yarn build
```

That's it!
It's code time 🚀
