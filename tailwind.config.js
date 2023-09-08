module.exports = {

  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        'sans': ['sans-serif'],
        'relics': ['Times New Roman', 'MS PGothic'],
        'ui': ['system-ui', '-apple-system']        
      },
      zIndex: {
        '-10': '-10',
      }      
    },
  },
  plugins: [],
}
