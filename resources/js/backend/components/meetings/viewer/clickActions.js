export default {
  methods: {
    clickAction(element,data) {
      function onClick(event) {
        // data variable will be updated in update method below so it will be always actual

        window.open(data.item.link,"_blank")
      }

      element.addEventListener('click',onClick)

      return {
        update(element,newData) {
          data = newData // data from parent scope updated
        },

        destroy(element,data) {
          element.removeEventListener('click',onClick)
        }
      }
    }
  }
}