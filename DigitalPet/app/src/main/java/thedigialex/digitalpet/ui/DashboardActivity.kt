package thedigialex.digitalpet.ui

import android.os.Bundle
import android.view.View
import android.widget.ProgressBar
import androidx.appcompat.app.AppCompatActivity
import thedigialex.digitalpet.R
import thedigialex.digitalpet.api.FetchService
import thedigialex.digitalpet.controller.CaseController
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.util.DataManager

class DashboardActivity : AppCompatActivity() {
    private lateinit var caseController: CaseController
    private lateinit var user: User
    private lateinit var fetchService: FetchService

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_dashboard)
       // fetchService = FetchService(this) { isLoading -> showLoading(isLoading) }
        user = DataManager.getUser(applicationContext)!!
        caseController = CaseController(findViewById(R.id.caseBackground),this, fetchService, user)
        if (user.mainDigitalMonster == null) {
           //fetchService.getEggs { eggs ->
           //    caseController.setUpSelectableDigitalMonster(eggs)
           //}
        }
        else {
            fetchService.setUpSpriteImages(user.mainDigitalMonster!!) { updatedMonster ->
                user.mainDigitalMonster = updatedMonster
                user.mainDigitalMonster!!.digitalMonster.animation(findViewById(R.id.mainImageView), 1)
                caseController.updateBackground(user.mainDigitalMonster!!.sleepStartedAt != null)
            }
        }
        user.inventoryItems?.let { items ->
            for (i in items.indices) {
                if (items[i].item.type == "consumable") {
                    fetchService.setupSpriteAndReturnItem(items[i]) { updatedItem ->
                        updatedItem?.let {
                            user.inventoryItems = user.inventoryItems!!.toMutableList().apply {
                                this[i] = it
                            }
                        }
                    }
                }
            }
        }
        user.trainingEquipments?.let { trainingEquipments ->
            for (i in trainingEquipments.indices) {
                fetchService.setupSpriteAndReturnTrainingEquipment(trainingEquipments[i]) { updatedItem ->
                    updatedItem?.let {
                        user.trainingEquipments = user.trainingEquipments!!.toMutableList().apply {
                            this[i] = it
                        }
                    }
                }
            }
        }
    }

    override fun onPause() {
        super.onPause()
        if(user.mainDigitalMonster != null) {
            fetchService.saveUserDigitalMonster(user.mainDigitalMonster!!)
        }
    }

    private fun showLoading(isLoading: Boolean) {
        val progressBar = findViewById<ProgressBar>(R.id.progressBar)
        progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
    }

  // fun evoTest(view: View)  {
  //     fetchService.evoUserDigitalMonster { newUserDigitalMonster ->
  //         newUserDigitalMonster?.let { newMonster ->
  //             user.mainDigitalMonster = newMonster
  //             user.mainDigitalMonster!!.digitalMonster.animation(findViewById(R.id.mainImageView), 1)
  //         }
  //     }
  // }
}