package thedigialex.digitalpet.controller

import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import thedigialex.digitalpet.R

class CaseController(private val parentLayout: ViewGroup, private val caseButtons: Array<Button>, private val menuImages: Array<ImageView>) {
    private var menuCycle: Int = -1
    private var menuId: Int = -1
    private var innerMenuCycle: Int = 0
    private var isMenuOpen: Boolean = false
    private var isSettings = false
    private val emptyMenuImageResources = IntArray(8)
    private val filledMenuImageResources = IntArray(8)

    init {
        setupImageResources()
        setupButtons()
    }

    private fun setupButtons() {
        caseButtons[0].setOnClickListener { accept() }
        caseButtons[1].setOnClickListener { cancel() }
        caseButtons[2].setOnClickListener { select(-1) }
        caseButtons[3].setOnClickListener { select(1) }
    }

    private fun setupImageResources() {
        emptyMenuImageResources[0] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        emptyMenuImageResources[1] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        emptyMenuImageResources[2] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        emptyMenuImageResources[3] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        emptyMenuImageResources[4] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        emptyMenuImageResources[5] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        emptyMenuImageResources[6] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        emptyMenuImageResources[7] = if (!isSettings) R.drawable.statsmenuempty else R.drawable.feedmenuempty
        filledMenuImageResources[0] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        filledMenuImageResources[1] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        filledMenuImageResources[2] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        filledMenuImageResources[3] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        filledMenuImageResources[4] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        filledMenuImageResources[5] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        filledMenuImageResources[6] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        filledMenuImageResources[7] = if (!isSettings) R.drawable.chartfull else R.drawable.feedmenufull
        updateMenuImages()
    }

    private fun updateMenuImages() {
        menuImages.forEachIndexed { i, imageView ->
            imageView.setBackgroundResource(if (i == menuCycle) filledMenuImageResources[i] else emptyMenuImageResources[i])
        }
    }

    private fun updateMenuLayout() {
        val titleTextView = parentLayout.findViewById<TextView>(R.id.title)
        when (menuId) {
            0 ->  titleTextView.text =  if (!isSettings) "Accept 0" else "Settings 0"
            1 ->  titleTextView.text = if (!isSettings) "Accept 1" else "Settings 1"
            2 ->  titleTextView.text = if (!isSettings) "Accept 2" else "Settings 2"
            3 ->  titleTextView.text = if (!isSettings) "Accept 3" else "Settings 3"
            4 ->  titleTextView.text = if (!isSettings) "Accept 4" else "Settings 4"
            5 ->  titleTextView.text = if (!isSettings) "Accept 5" else "Settings 5"
            6 ->  titleTextView.text = if (!isSettings) "Accept 6" else "Settings 6"
            7 ->  titleTextView.text = if (!isSettings) "Accept 7" else "Settings 7"
        }

    }

    private fun select(direction: Int) {
        if(isMenuOpen) {

        }
        else {
            menuCycle = (menuCycle + direction + 8) % 8
            updateMenuImages()
        }
    }

    private fun cancel() {
        if(isMenuOpen) {
            isMenuOpen = false
            innerMenuCycle = 0
            menuId = -1
            parentLayout.visibility = View.GONE
        }
        else {
            menuCycle = -1
            updateMenuImages()
        }
    }

    private fun accept() {
        if(!isMenuOpen && menuCycle != -1) {
            isMenuOpen = true
            menuId = menuCycle
            parentLayout.visibility = View.VISIBLE
            updateMenuLayout()
        }
        else {

        }
    }

    fun switchMenu() {
        if(!isMenuOpen){
            isSettings = !isSettings
            setupImageResources()
        }
    }
}
