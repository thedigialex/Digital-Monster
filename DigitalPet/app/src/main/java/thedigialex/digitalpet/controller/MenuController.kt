package thedigialex.digitalpet.controller

import android.annotation.SuppressLint
import android.graphics.Bitmap
import android.view.View
import android.view.ViewGroup
import android.widget.EditText
import android.widget.ImageView
import android.widget.TextView
import androidx.constraintlayout.widget.ConstraintLayout
import thedigialex.digitalpet.R

@SuppressLint("SetTextI18n")
class MenuController(private val menuLayout: ViewGroup) {
    private var title: TextView = menuLayout.findViewById(R.id.title)
    private var count: TextView = menuLayout.findViewById(R.id.count)
    private var alertText: TextView = menuLayout.findViewById(R.id.alertText)
    private var detailsText: TextView = menuLayout.findViewById(R.id.detailsText)
    private var iconImage: ImageView = menuLayout.findViewById(R.id.iconImage)
    private var statsViewLayout: ConstraintLayout = menuLayout.findViewById(R.id.statsViewLayout)
    var editText: EditText = menuLayout.findViewById(R.id.editInput)

    var currentOpenMenuId: Int = -1
    var menuCycle: Int = 0
    var isMenuOpen: Boolean = false
    var isSettings: Boolean = false
    var stats: Array<String> = Array(12) { "0" }
    var subMenuImageResources = mutableListOf<Int>()
    var menuImageResources: MutableList<Bitmap> = mutableListOf()
    private var allTitles: MutableList<String> = mutableListOf()
    private var menuMaxCycle: Int = 0

    fun cycleMenu(direction: Int) {
        alertText.visibility = View.GONE
        if(menuMaxCycle != 0) {
            menuCycle = (menuCycle + direction + menuMaxCycle) % menuMaxCycle
            when (currentOpenMenuId) {
                -10 ->  updateIconImage(false)
                0 -> cycleStatMenu()
                1 -> updateIconImage(false)
                2 -> updateIconImage(false)
                3 -> updateIconImage(false)
                4 -> updateIconImage(false)
                5 -> updateIconImage(true)
                6 -> updateIconImage(true)
                7 -> updateIconImage(true)
                8 -> updateIconImage(false)
            }
            count.text = "${menuCycle + 1} / $menuMaxCycle"
        }
        else {
            count.text = "0 / 0"
        }
    }

    fun displayMessage(message: String) {
        alertText.text = message
        alertText.visibility = View.VISIBLE
    }

    fun reset() {
        menuLayout.visibility = View.GONE
        isMenuOpen = false
        menuCycle = 0
    }

    fun openMenu(menuIdToOpen: Int, maxCycle: Int, imageResources: MutableList<Bitmap>, titles: MutableList<String>) {
        isMenuOpen = true
        menuMaxCycle = maxCycle
        currentOpenMenuId = menuIdToOpen
        menuImageResources = imageResources
        allTitles = titles
        menuCycle = 0

        menuLayout.visibility = View.VISIBLE
        alertText.visibility = View.GONE
        editText.visibility = View.GONE
        iconImage.visibility = View.GONE
        detailsText.visibility = View.GONE
        statsViewLayout.visibility = View.GONE

        when (currentOpenMenuId) {
            -10 ->  {
                title.text = "Select an Egg"
                showIconImage()
                editText.visibility = View.VISIBLE
                updateIconImage(false)
            }
            0 -> {
                if (!isSettings) {
                    title.text = "Stats"
                    statsViewLayout.visibility = View.VISIBLE
                    cycleStatMenu()
                } else {
                    title.text = "Settings 0"
                }
            }
            1 -> {
                if (!isSettings) {
                    title.text = "Food"
                    if (menuImageResources.isEmpty()) {
                        displayMessage("No Consumable Items")
                    } else {
                        showIconImage()
                        updateIconImage(false)
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            2 ->  {
                if (!isSettings) {
                    title.text = "Training"
                    if (menuImageResources.isEmpty()) {
                        displayMessage("No Training Equipment")
                    } else {
                        showIconImage()
                        updateIconImage(false)
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            3 -> {
                if (!isSettings) {
                    title.text = "Cleaning"
                    if (menuImageResources.isEmpty()) {
                        displayMessage("No Cleaning Equipment")
                    } else {
                        showIconImage()
                        updateIconImage(false)
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            4 ->  {
                if (!isSettings) {
                    title.text = "Lighting"
                    if (menuImageResources.isEmpty()) {
                        displayMessage("No Lighting Equipment")
                    } else {
                        showIconImage()
                        updateIconImage(false)
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            5 ->  {
                if (!isSettings) {
                    title.text = "Battle"
                    showIconImage()
                    updateIconImage(true)
                } else {
                    title.text = "Settings 1"
                }
            }
            6 ->  {
                if (!isSettings) {
                    title.text = "Game"
                    showIconImage()
                    updateIconImage(true)
                } else {
                    title.text = "Settings 1"
                }
            }
            7 ->  {
                if (!isSettings) {
                    title.text = "Shop"
                    showIconImage()
                    updateIconImage(true)
                } else {
                    title.text = "Settings 1"
                }
            }
            8 -> {
                if (!isSettings) {
                    title.text = "Item for sale"
                    showIconImage()
                    updateIconImage(false)
                } else {
                    title.text = "Settings 1"
                }
            }
        }
        count.text = if (menuMaxCycle != 0) "${menuCycle + 1} / $menuMaxCycle" else "0 / 0"
    }

    private fun cycleStatMenu() {
        val statTextViews = listOf(
            R.id.statTextTopLeft,
            R.id.statTextTopRight,
            R.id.statTextBottomLeft,
            R.id.statTextBottomRight
        ).map { menuLayout.findViewById<TextView>(it).apply { text = null; background = null } }
        val energyBars = listOf(
            R.drawable.energy_bar_0, R.drawable.energy_bar_25, R.drawable.energy_bar_50,
            R.drawable.energy_bar_75, R.drawable.energy_bar_100)
        when (menuCycle) {
            0 -> {
                statTextViews[0].text = "Level\n${stats[0]}"
                statTextViews[1].text = "Stage\n${stats[1]}"
                statTextViews[2].text = "Training\n${stats[2]}"
                statTextViews[3].text = "Battle\n${stats[3]}"
            }
            1 -> {
                statTextViews[0].text = "Strength\n${stats[4]}"
                statTextViews[1].text = "Defense\n${stats[5]}"
                statTextViews[2].text = "Agility\n${stats[6]}"
                statTextViews[3].text = "Mind\n${stats[7]}"
            }
            2 -> {
                statTextViews[0].text = "Hunger"
                statTextViews[1].setBackgroundResource(energyBars[Integer.parseInt(stats[8])])
                statTextViews[2].text = "Exercise"
                statTextViews[3].setBackgroundResource(energyBars[Integer.parseInt(stats[9])])
            }
            3 -> {
                statTextViews[0].text = "Energy"
                statTextViews[1].setBackgroundResource(energyBars[Integer.parseInt(stats[10])])
                statTextViews[2].text = "Evo Progress"
                statTextViews[3].setBackgroundResource(energyBars[Integer.parseInt(stats[11])])
            }
        }
    }

    private fun showIconImage() {
        iconImage.visibility = View.VISIBLE
        detailsText.visibility = View.VISIBLE
    }

    private fun updateIconImage(updateSub: Boolean) {
        if(updateSub) {
            iconImage.setImageBitmap(null)
            iconImage.setBackgroundResource(subMenuImageResources[menuCycle])
        }
        else {
            iconImage.background = null
            iconImage.setImageBitmap(menuImageResources[menuCycle])
        }
        detailsText.text = allTitles[menuCycle]
    }
}