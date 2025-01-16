package thedigialex.digitalpet.controller

import android.graphics.Bitmap
import android.view.View
import android.view.ViewGroup
import android.widget.EditText
import android.widget.ImageView
import android.widget.TextView
import androidx.constraintlayout.widget.ConstraintLayout
import thedigialex.digitalpet.R

class MenuController(private val menuLayout: ViewGroup) {
    private var currentOpenMenuId: Int = -1
    private var menuCycle: Int = 0
    private var menuMaxCycle: Int = 0
    private var isMenuOpen: Boolean = false
    private var isSettings: Boolean = false
    private var menuImageResources: MutableList<Bitmap> = mutableListOf()


    private var title: TextView = menuLayout.findViewById(R.id.title)
    private var count: TextView = menuLayout.findViewById(R.id.count)
    private var alertText: TextView = menuLayout.findViewById(R.id.alertText)
    private var editText: EditText = menuLayout.findViewById(R.id.editInput)
    private var iconImage: ImageView = menuLayout.findViewById(R.id.iconImage)
    private var statsViewLayout: ConstraintLayout = menuLayout.findViewById(R.id.statsViewLayout)


    fun openMenu(menuIdToOpen: Int, maxCycle: Int, imageResources: MutableList<Bitmap>) {
        isMenuOpen = true
        menuMaxCycle = maxCycle
        currentOpenMenuId = menuIdToOpen
        menuImageResources = imageResources

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
            }
            0 -> {
                if (!isSettings) {
                    title.text = "Stats"
                    menuMaxCycle = 4
                    statsViewLayout.visibility = View.VISIBLE
                    //updateStatMenu()
                } else {
                    title.text = "Settings 0"
                }
            }
            1 -> {
                if (!isSettings) {
                    title.text = "Food"
                    if (menuMaxCycle == 0) {
                        displayMessage("No Consumable Items")
                    } else {
                        iconImage.visibility = View.VISIBLE
                        //updateMenuIcon()
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
        count.text = "${menuCycle + 1} / $menuMaxCycle"
    }

    private fun displayMessage(message: String) {
        alertText.text = message
        alertText.visibility = View.VISIBLE
    }
}