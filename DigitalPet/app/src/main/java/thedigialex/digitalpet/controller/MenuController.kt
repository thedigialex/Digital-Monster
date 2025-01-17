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
    var editText: EditText = menuLayout.findViewById(R.id.editInput)
    private var iconImage: ImageView = menuLayout.findViewById(R.id.iconImage)
    private var statsViewLayout: ConstraintLayout = menuLayout.findViewById(R.id.statsViewLayout)

    var currentOpenMenuId: Int = -1
    private var menuCycle: Int = 0
    private var menuMaxCycle: Int = 0
    var isMenuOpen: Boolean = false
    var isSettings: Boolean = false
    var stats: Array<String> = Array(12) { "0" }
    var menuImageResources: MutableList<Bitmap> = mutableListOf()


    fun cycleMenu(direction: Int) {
        if(menuMaxCycle != 0){
            menuCycle = (menuCycle + direction + menuMaxCycle) % menuMaxCycle
            when (currentOpenMenuId) {
                -10 ->  updateIconImage()
                0 -> cycleStatMenu()
                1 -> updateIconImage()
                2 -> updateIconImage()
                7 -> updateIconImage()
                8 -> updateIconImage()
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

    fun openMenu(menuIdToOpen: Int, maxCycle: Int, imageResources: MutableList<Bitmap>) {
        isMenuOpen = true
        menuMaxCycle = maxCycle
        currentOpenMenuId = menuIdToOpen
        menuImageResources = imageResources
        menuCycle = 0

        menuLayout.visibility = View.VISIBLE
        alertText.visibility = View.GONE
        editText.visibility = View.GONE
        iconImage.visibility = View.GONE
        statsViewLayout.visibility = View.GONE

        when (currentOpenMenuId) {
            -10 ->  {
                title.text = "Select an Egg"
                iconImage.visibility = View.VISIBLE
                editText.visibility = View.VISIBLE
                updateIconImage()
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
                        iconImage.visibility = View.VISIBLE
                        updateIconImage()
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            2 ->  {
                if (!isSettings) {

                    title.text = "Training"
                    if (menuMaxCycle == 0) {
                        displayMessage("No Training Equipment")
                    } else {
                        iconImage.visibility = View.VISIBLE
                        //updateMenuIcon()
                    }
                } else {
                    title.text = "Settings 1"
                }
            }
            3 -> {
                if(!isSettings) {
                    //performAction("cleaning")
                }
                else {
                    title.text = "Settings 3"
                }
            }
            4 ->  {
                if(!isSettings) {
                    //switchLight()
                }
                else {
                    title.text = "Settings 3"
                }
            }
            5 ->  title.text = if (!isSettings) "Accept 5" else "Settings 5"
            6 ->  title.text = if (!isSettings) "Accept 6" else "Settings 6"
            7 -> {
                if (!isSettings) {
                    title.text = "Shop"
                    if (menuMaxCycle == 0) {
                        displayMessage("No Items")
                    } else {
                        iconImage.visibility = View.VISIBLE
                        //updateMenuIcon()
                    }
                } else {
                    title.text = "Settings 7"
                }
            }
            8 -> {
                title.text = "Items For Sale"
                if (menuMaxCycle == 0) {
                    displayMessage("No Items")
                } else {
                    iconImage.visibility = View.VISIBLE
                    //updateMenuIcon()
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
                statTextViews[0].text = "Level\n${stats.get(0)}"
                statTextViews[1].text = "Stage\n${stats.get(1)}"
                statTextViews[2].text = "Training\n${stats.get(2)}"
                statTextViews[3].text = "Battle\n${stats.get(3)}"
            }
            1 -> {
                statTextViews[0].text = "Strength\n${stats.get(4)}"
                statTextViews[1].text = "Defense\n${stats.get(5)}"
                statTextViews[2].text = "Agility\n${stats.get(6)}"
                statTextViews[3].text = "Mind\n${stats.get(7)}"
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

    private fun updateIconImage() {
        val  iconImage = menuLayout.findViewById<ImageView>(R.id.iconImage)
        iconImage.setImageBitmap(menuImageResources?.get(menuCycle))
    }
}